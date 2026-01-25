<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Xendit\Xendit;
use Xendit\Invoice;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Calculate and create payment invoice via Xendit
     */
    public function store(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Check authorization
        if (Auth::user()->isCustomer() && $booking->customer_id !== Auth::id()) {
            abort(403);
        }

        // Validate basic input if any notes
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Setup Xendit API Key (V2 Style)
            $apiKey = config('services.xendit.secret_key');
            if (empty($apiKey)) {
                 // Fallback to env if config is empty for some reason
                 $apiKey = env('XENDIT_API_KEY');
            }
            
            Xendit::setApiKey($apiKey);

            // Generate External ID
            $externalId = 'INV-' . time() . '-' . $booking->id;
            
            // Create Invoice using V2 static method
            $params = [
                'external_id' => $externalId,
                'amount' => $booking->total_price,
                'description' => 'Payment for Booking #' . $booking->booking_code . ' via WINWIN Makeup',
                'invoice_duration' => 3600, // 1 hour
                'payer_email' => Auth::user()->email,
                'customer' => [
                    'given_names' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'mobile_number' => Auth::user()->phone ?? null,
                ],
                'currency' => 'IDR',
                'success_redirect_url' => route('dashboard'),
                'failure_redirect_url' => route('dashboard'),
                'items' => [
                    [
                        'name' => $booking->package->name,
                        'quantity' => 1,
                        'price' => $booking->total_price,
                        'category' => 'Makeup Service'
                    ]
                ]
            ];

            $invoice = \Xendit\Invoice::create($params);
            
            // Save Payment as Pending with Invoice URL
            // V2 returns an array
            $invoiceUrl = $invoice['invoice_url'];
            
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'payment_code' => $externalId,
                'payment_method' => 'xendit_invoice',
                'amount' => $booking->total_price,
                'status' => 'pending',
                'external_id' => $externalId,
                'checkout_link' => $invoiceUrl,
                'notes' => $request->notes,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice berhasil dibuat.',
                    'invoice_url' => $invoiceUrl
                ]);
            }

            return redirect($invoiceUrl);

        } catch (\Exception $e) {
            Log::error('Xendit Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat invoice pembayaran: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal membuat invoice pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $payment = Payment::with(['booking.customer', 'booking.package'])
            ->findOrFail($id);

        if (Auth::user()->isCustomer() && $payment->booking->customer_id !== Auth::id()) {
            abort(403);
        }

        return view('payment.show', compact('payment'));
    }

    public function verify(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $payment = Payment::with('booking')->findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:verified,paid,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ];

        if ($validated['status'] === 'rejected') {
            $updateData['rejection_reason'] = $validated['rejection_reason'];
        }

        $payment->update($updateData);

        if ($validated['status'] === 'verified' || $validated['status'] === 'paid') {
            $payment->booking->update(['status' => 'confirmed', 'confirmed_at' => now()]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Status updated.');
    }
}
