<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log incoming webhook for debugging
        Log::info('Xendit Webhook Received:', $request->all());

        // Verify Verification Token (Security best practice)
        $reqToken = $request->header('x-callback-token');
        $myToken = env('XENDIT_WEBHOOK_VERIFICATION_TOKEN');

        if ($reqToken !== $myToken) {
            Log::warning('Xendit Webhook Token Mismatch. Received: ' . $reqToken);
            return response()->json(['message' => 'Invalid Token'], 403);
        }

        // Handle Invoice Callback
        // Check if this is an invoice update
        if ($request->has('external_id') && $request->has('status')) {
            $externalId = $request->external_id;
            $status = $request->status;
            Log::info("Processing Xendit Webhook for External ID: $externalId with Status: $status");
            // Xendit statuses: PENDING, PAID, SETTLED, EXPIRED
            
            $payment = Payment::where('external_id', $externalId)->first();

            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Update Payment Status
            $payment->status = strtolower($status); // pending, paid, settled, expired
            
            // Capture specific payment method from Xendit if available
            if ($request->has('payment_method')) {
                $payment->payment_method = 'xendit_' . strtolower($request->payment_method);
            }
            if ($request->has('payment_channel')) {
                $payment->payment_method = 'xendit_' . strtolower($request->payment_channel);
            }

            if ($status == 'PAID' || $status == 'SETTLED') {
                $payment->status = 'paid'; // normalize to our system
                $payment->verified_at = Carbon::now();
                $payment->verified_by = null; // System verified
                
                // If payment method is present, we consider it paid as per user request
                $payment->save();

                // Automatically Confirm Booking
                if ($payment->booking) {
                    $payment->booking->status = 'confirmed';
                    $payment->booking->confirmed_at = Carbon::now();
                    $payment->booking->save();
                }
            } else if ($status == 'EXPIRED') {
                $payment->status = 'expired'; 
                $payment->save();
                
                // Cancel booking if expired
                $payment->booking->status = 'rejected';
                $payment->booking->rejection_reason = 'Pembayaran kadaluarsa (otomatis)';
                $payment->booking->save();
            } else {
                $payment->save();
            }

            return response()->json(['message' => 'Webhook received']);
        }

        return response()->json(['message' => 'Ignoring event'], 200);
    }
}
