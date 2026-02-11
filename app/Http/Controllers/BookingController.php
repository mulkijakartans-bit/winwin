<?php

namespace App\Http\Controllers;

use App\Booking;
use App\MuaProfile;
use App\MakeupPackage;
use App\PackageAddOn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request)
    {
        $packageId = $request->package_id;
        $package = MakeupPackage::findOrFail($packageId);
        $muaProfile = MuaProfile::getWinwinProfile();

        return view('booking.create', compact('muaProfile', 'package'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:makeup_packages,id',
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required',
            'event_location' => 'required|string|max:500|in:Tegal Kota,Kabupaten Tegal,Brebes Kota,Brebes Kabupaten,Pemalang',
            // 'event_type' => 'required|string|max:255|in:wedding,khitan,engagement,wisuda,event,lainnya',
            'notes' => 'nullable|string|max:1000',
            'selected_add_ons' => 'nullable|array',
            'selected_add_ons.*' => 'integer|exists:package_add_ons,id',
            'custom_addon_id' => 'nullable|exists:add_ons,id',
        ]);

        // Check if date already has 3 bookings
        $bookingCount = Booking::where(function($query) {
                $query->whereIn('status', ['confirmed', 'on_progress'])
                      ->orWhere(function($q) {
                          $q->where('status', 'pending')
                            ->where('created_at', '>=', now()->subMinutes(15));
                      });
            })
            ->where('booking_date', $validated['booking_date'])
            ->count();

        if ($bookingCount >= 3) {
            return back()->withErrors([
                'booking_date' => 'Tanggal ini sudah penuh (maksimal 3 booking per hari).'
            ])->withInput();
        }

        // Check if the selected time overlaps with existing bookings (5 hours duration)
        $selectedTime = \Carbon\Carbon::parse($validated['booking_time']);
        $selectedHour = (int)$selectedTime->format('H');

        // Ensure booking finishes by 22:00 (closing time)
        if ($selectedHour > 17) {
            return back()->withErrors([
                'booking_time' => 'Booking tidak tersedia setelah pukul 17:00 karena kami tutup pukul 22:00.'
            ])->withInput();
        }

        $selectedEndHour = $selectedHour + 6; // 6 hours total block (5h work + 1h buffer)

        $existingBookings = Booking::where(function($query) {
                $query->whereIn('status', ['confirmed', 'on_progress'])
                      ->orWhere(function($q) {
                          $q->where('status', 'pending')
                            ->where('created_at', '>=', now()->subMinutes(15));
                      });
            })
            ->where('booking_date', $validated['booking_date'])
            ->get();

        foreach ($existingBookings as $existingBooking) {
            $existingTime = \Carbon\Carbon::parse($existingBooking->booking_time);
            $existingHour = (int)$existingTime->format('H');
            $existingEndHour = $existingHour + 6; // 6 hours total block (5h work + 1h buffer)

            // Check for overlap: new booking starts before existing ends AND new booking ends after existing starts
            if ($selectedHour < $existingEndHour && $selectedEndHour > $existingHour) {
                return back()->withErrors([
                    'booking_time' => 'Waktu ini tidak tersedia karena overlap dengan booking yang sudah ada. Setiap pemesanan berlaku untuk 5 jam dan kami close pukul 22:00 WIB.'
                ])->withInput();
            }
        }

        $package = MakeupPackage::findOrFail($validated['package_id']);

        // Calculate total price including add-ons
        $basePrice = $package->price;
        $addOnsTotal = 0;
        $selectedAddOns = [];

        if (!empty($validated['selected_add_ons'])) {
            $addOns = PackageAddOn::whereIn('id', $validated['selected_add_ons'])
                ->where('package_id', $validated['package_id'])
                ->where('is_active', true)
                ->get();

            foreach ($addOns as $addOn) {
                $addOnsTotal += $addOn->price;
                $selectedAddOns[] = [
                    'id' => $addOn->id,
                    'name' => $addOn->name,
                    'price' => $addOn->price
                ];
            }
        }

        // Handle Global Add-On
        if (!empty($validated['custom_addon_id'])) {
            $globalAddOn = \App\AddOn::find($validated['custom_addon_id']);
            if ($globalAddOn && $globalAddOn->is_active) {
                $addOnsTotal += $globalAddOn->default_price;
                $selectedAddOns[] = [
                    'id' => $globalAddOn->id,
                    'name' => $globalAddOn->name,
                    'price' => $globalAddOn->default_price,
                    'type' => 'global'
                ];
            }
        }

        $totalPrice = $basePrice + $addOnsTotal;

        $booking = Booking::create([
            'customer_id' => Auth::id(),
            'package_id' => $validated['package_id'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'event_location' => $validated['event_location'],
            // 'event_type' => $validated['event_type'],
            'notes' => $validated['notes'],
            'selected_add_ons' => $selectedAddOns,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');
    }

    /**
     * Display the specified booking.
     */
    public function show($id)
    {
        $booking = Booking::with(['customer', 'package', 'payment', 'review'])
            ->findOrFail($id);

        // Check authorization
        if (Auth::user()->isCustomer() && $booking->customer_id !== Auth::id()) {
            abort(403);
        }

        $muaProfile = MuaProfile::getWinwinProfile();

        return view('booking.show', compact('booking', 'muaProfile'));
    }

    /**
     * Display a listing of bookings.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isCustomer()) {
            $bookings = $user->customerBookings()
                ->with(['package', 'payment'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Admin melihat semua booking
            $bookings = Booking::with(['customer', 'package', 'payment'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('booking.index', compact('bookings'));
    }

    /**
     * Update booking status (for Admin).
     */
    public function updateStatus(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:confirmed,rejected,on_progress,completed',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500',
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'confirmed') {
            $updateData['confirmed_at'] = now();
        } elseif ($validated['status'] === 'completed') {
            $updateData['completed_at'] = now();
        } elseif ($validated['status'] === 'rejected') {
            $updateData['rejection_reason'] = $validated['rejection_reason'];
        }

        $booking->update($updateData);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Status booking berhasil diupdate.']);
        }

        return redirect()->route('booking.show', $booking->id)
            ->with('success', 'Status booking berhasil diupdate.');
    }
}
