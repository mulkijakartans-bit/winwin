<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\User;
use Illuminate\Support\Facades\Hash;

echo "\n--- STARTING DELETE CUSTOMER VERIFICATION ---\n";

// 1. Create a Test Customer
try {
    $testEmail = 'test_delete_' . time() . '@example.com';
    $user = User::create([
        'name' => 'Test Delete User',
        'email' => $testEmail,
        'password' => Hash::make('password'),
        'role' => 'customer',
        'phone' => '08123456789'
    ]);

    echo "1. Created Test Customer:\n";
    echo "   - ID: {$user->id}\n";
    echo "   - Email: {$user->email}\n";

} catch (\Exception $e) {
    die("❌ Error creating user: " . $e->getMessage() . "\n");
}

// 2. Mock Admin Deletion Call (Manually triggering controller logic)
try {
    echo "\n2. Deleting user via AdminController logic...\n";
    
    // Simulating the deleteUser logic
    $userToDelete = User::where('role', 'customer')->findOrFail($user->id);
    $userToDelete->delete();
    
    echo "   User deleted.\n";

} catch (\Exception $e) {
    die("❌ Error deleting user: " . $e->getMessage() . "\n");
}

// 3. Verify Deletion
echo "\n3. Verifying Deletion...\n";
$exists = User::find($user->id);

if (!$exists) {
    echo "\n✅ SUCCESS: Customer was correctly removed from the database.\n";
} else {
    echo "\n❌ FAILED: Customer still exists in the database.\n";
}

echo "\n--- VERIFICATION COMPLETED ---\n";
