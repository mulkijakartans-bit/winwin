<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a payment.
     */
    public function create($bookingId)
    {
        $booking = Booking::with(['package', 'payment'])->findOrFail($bookingId);

        // Check authorization
        if (Auth::user()->isCustomer() && $booking->customer_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment) {
            return redirect()->route('payment.show', $booking->payment->id);
        }

        return view('payment.create', compact('booking'));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Check authorization
        if (Auth::user()->isCustomer() && $booking->customer_id !== Auth::id()) {
            abort(403);
        }

        try {
            $validated = $request->validate([
                'payment_method' => 'required|in:bank_transfer,cash,e_wallet,other',
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'notes' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        // Upload payment proof to public/storage
        $file = $request->file('payment_proof');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $directory = public_path('storage/payment_proofs');
        
        // Create directory if not exists
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $file->move($directory, $fileName);
        $proofPath = 'payment_proofs/' . $fileName;

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => $validated['payment_method'],
            'amount' => $booking->total_price,
            'payment_proof' => $proofPath,
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diupload. Menunggu verifikasi.',
                'payment' => $payment->load('booking')
            ]);
        }

        return redirect()->route('payment.show', $payment->id)
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi.');
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $payment = Payment::with(['booking.customer', 'booking.package', 'verifier'])
            ->findOrFail($id);

        // Check authorization
        $booking = $payment->booking;
        if (Auth::user()->isCustomer() && $booking->customer_id !== Auth::id()) {
            abort(403);
        }

        return view('payment.show', compact('payment'));
    }

    /**
     * Verify payment (Admin only).
     */
    public function verify(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $payment = Payment::with('booking')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:verified,rejected',
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

        // Update booking status if payment verified
        if ($validated['status'] === 'verified') {
            $payment->booking->update(['status' => 'confirmed', 'confirmed_at' => now()]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Status pembayaran berhasil diupdate.']);
        }
        
        return redirect()->route('payment.show', $payment->id)
            ->with('success', 'Status pembayaran berhasil diupdate.');
    }
}
