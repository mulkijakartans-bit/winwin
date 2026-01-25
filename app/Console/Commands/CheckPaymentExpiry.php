<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckPaymentExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for pending payments that have exceeded the 1 hour limit and cancel them';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for expired payments...');

        // Find pending payments created more than 65 minutes ago (1 hour + 5 mins buffer)
        // We use created_at of the Payment record
        $expiryTime = Carbon::now()->subMinutes(65);

        $expiredPayments = Payment::where('status', 'pending')
            ->where('created_at', '<', $expiryTime)
            ->with('booking')
            ->get();

        $count = 0;

        foreach ($expiredPayments as $payment) {
            try {
                // Mark payment as expired
                $payment->status = 'expired';
                $payment->save();

                // Cancel booking
                if ($payment->booking && $payment->booking->status === 'pending') {
                    $payment->booking->status = 'cancelled';
                    $payment->booking->rejection_reason = 'Pembayaran kadaluarsa (by system)';
                    $payment->booking->save();
                    
                    $this->info("Expired payment ID: {$payment->id}, Booking: {$payment->booking->booking_code}");
                    $count++;
                }
            } catch (\Exception $e) {
                Log::error("Failed to expire payment {$payment->id}: " . $e->getMessage());
                $this->error("Error processing payment {$payment->id}");
            }
        }

        $this->info("Processed {$count} expired payments.");
        return 0;
    }
}
