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
    protected $description = 'Check for pending bookings that have exceeded the 15 minute limit and cancel them';

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
        // Find pending bookings created more than 15 minutes ago
        $expiryTime = Carbon::now()->subMinutes(15);

        $expiredBookings = \App\Booking::where('status', 'pending')
            ->where('created_at', '<', $expiryTime)
            ->with('payment')
            ->get();

        $count = 0;

        foreach ($expiredBookings as $booking) {
            try {
                // If there is an associated pending payment, mark it as expired
                if ($booking->payment && $booking->payment->status === 'pending') {
                    $booking->payment->status = 'expired';
                    $booking->payment->save();
                }

                // Cancel the booking
                $booking->status = 'cancelled';
                $booking->rejection_reason = 'Booking hangus otomatis karena pembayaran tidak diselesaikan dalam 15 menit.';
                $booking->save();
                
                $this->info("Cancelled expired booking: {$booking->booking_code}");
                $count++;
            } catch (\Exception $e) {
                Log::error("Failed to reject booking {$booking->id}: " . $e->getMessage());
                $this->error("Error processing booking {$booking->id}");
            }
        }

        $this->info("Processed {$count} cancelled bookings.");
        return 0;
    }
}
