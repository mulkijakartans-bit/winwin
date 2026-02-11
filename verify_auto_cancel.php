<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Booking;
use App\MakeupPackage;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

echo "\n--- STARTING AUTO-CANCELLATION VERIFICATION ---\n";

// 1. Find necessary dependencies
$customer = User::where('role', 'customer')->first();
if (!$customer) {
    // Try to find any user if no explicit customer role
    $customer = User::first(); 
}

if (!$customer) {
    die("❌ Error: No users found to create a booking with.\n");
}

$package = MakeupPackage::first();
if (!$package) {
    die("❌ Error: No makeup packages found.\n");
}

// 2. Create the Test Booking
try {
    $booking = new Booking();
    $booking->customer_id = $customer->id;
    $booking->package_id = $package->id;
    $booking->booking_date = Carbon::tomorrow()->format('Y-m-d');
    $booking->booking_time = '10:00';
    $booking->event_location = 'Tegal Kota';
    $booking->total_price = 100000;
    $booking->status = 'pending';
    // Backdate to 70 minutes ago
    $booking->created_at = Carbon::now()->subMinutes(70);
    $booking->booking_code = 'TEST-' . strtoupper(Str::random(6));
    $booking->save();

    echo "1. Created Test Booking:\n";
    echo "   - ID: {$booking->id}\n";
    echo "   - Code: {$booking->booking_code}\n";
    echo "   - Created At: {$booking->created_at} ( > 1 hour ago)\n";
    echo "   - Initial Status: {$booking->status}\n";

} catch (\Exception $e) {
    die("❌ Error creating booking: " . $e->getMessage() . "\n");
}

// 3. Run the Expiry Command
echo "\n2. Running 'php artisan payments:check-expiry'...\n";
Artisan::call('payments:check-expiry');
$output = Artisan::output();
echo "   Command Output: " . trim($output) . "\n";

// 4. Verify Result
echo "\n3. Verifying Status...\n";
$booking->refresh();
echo "   - Final Status: {$booking->status}\n";
echo "   - Rejection Reason: {$booking->rejection_reason}\n";

if ($booking->status === 'rejected') {
    echo "\n✅ SUCCESS: Booking was correctly rejected.\n";
} else {
    echo "\n❌ FAILED: Booking status should be 'rejected'.\n";
}

// 5. Cleanup
echo "\n4. Cleaning up test data...\n";
$booking->delete();
echo "   Test booking deleted.\n";
