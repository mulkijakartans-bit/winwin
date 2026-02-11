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
                'invoice_duration' => 900, // 15 minutes
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

    /**
     * Display a print-friendly view of the payment.
     */
    public function print($id)
    {
        $payment = Payment::with(['booking.customer', 'booking.package'])
            ->findOrFail($id);

        if (Auth::user()->isCustomer() && $payment->booking->customer_id !== Auth::id()) {
            abort(403);
        }

        return view('payment.print', compact('payment'));
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

    /**
     * Upload proof of payment
     */
    public function uploadProof(Request $request, $id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        // Check authorization
        if (Auth::user()->isCustomer() && $payment->booking->customer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('payment_proof')) {
                // Delete old proof if exists
                if ($payment->payment_proof && \Illuminate\Support\Facades\Storage::disk('public')->exists($payment->payment_proof)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($payment->payment_proof);
                }

                $file = $request->file('payment_proof');
                $filename = 'proof_' . time() . '_' . $payment->id . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('payment_proofs', $filename, 'public');

                $payment->update([
                    'payment_proof' => $path
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Bukti pembayaran berhasil diunggah.',
                        'proof_url' => asset('storage/' . $path)
                    ]);
                }

                return back()->with('success', 'Bukti pembayaran berhasil diunggah.');
            }
        } catch (\Exception $e) {
            Log::error('Upload Proof Error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengunggah bukti pembayaran.'], 500);
            }
            return back()->with('error', 'Gagal mengunggah bukti pembayaran.');
        }
    }

    /**
     * Download proof of payment
     */
    public function download($id)
    {
        $payment = Payment::with('booking')->findOrFail($id);

        // Check authorization (Admin can always download, Customer only their own)
        if (!Auth::user()->isAdmin() && $payment->booking->customer_id !== Auth::id()) {
            abort(403);
        }

        if ($payment->payment_proof && \Illuminate\Support\Facades\Storage::disk('public')->exists($payment->payment_proof)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->download($payment->payment_proof);
        }

        // Enhancement for Xendit or cases where there is no manual proof but it is paid
        if ($payment->status === 'paid' || $payment->status === 'verified') {
            return redirect()->route('payment.print', $payment->id);
        }

        return back()->with('error', 'Bukti pembayaran tidak ditemukan.');
    }
}
