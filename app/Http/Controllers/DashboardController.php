<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->customerDashboard();
        }
    }

    /**
     * Customer dashboard.
     */
    private function customerDashboard()
    {
        $user = Auth::user();
        $bookings = $user->customerBookings()
            ->with(['package', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($booking) {
                $booking->load('payment');
                return $booking;
            });

        $pendingBookings = $user->customerBookings()->where('status', 'pending')->count();
        $confirmedBookings = $user->customerBookings()->where('status', 'confirmed')->count();
        $completedBookings = $user->customerBookings()->where('status', 'completed')->count();

        // Get active packages for booking button
        $muaProfile = \App\MuaProfile::getWinwinProfile();
        $packages = $muaProfile->packages()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get active global add-ons
        $addOns = \App\AddOn::active()->orderBy('name')->get();

        return view('dashboard.customer', compact('user', 'bookings', 'pendingBookings', 'confirmedBookings', 'completedBookings', 'packages', 'muaProfile', 'addOns'));
    }

    /**
     * Admin dashboard.
     */
    private function adminDashboard()
    {
        $totalUsers = \App\User::count();
        $totalCustomers = \App\User::where('role', 'customer')->count();
        $totalBookings = \App\Booking::count();
        $pendingBookings = \App\Booking::where('status', 'pending')->count();
        $pendingPayments = \App\Payment::where('status', 'pending')->count();

        // Load initial data for tabs
        $users = \App\User::where('role', 'customer')->orderBy('created_at', 'desc')->limit(15)->get();
        $bookings = \App\Booking::with(['customer', 'package', 'payment'])->orderBy('created_at', 'desc')->limit(15)->get();
        $payments = \App\Payment::with(['booking.customer', 'verifier'])->orderBy('created_at', 'desc')->limit(15)->get();
        $muaProfile = \App\MuaProfile::getWinwinProfile();
        $portfolios = $muaProfile->portfolios()->orderBy('order')->get();
        $packages = $muaProfile->packages()->orderBy('order')->get();
        $addOns = \App\AddOn::orderBy('name')->get();

        $activeTab = request('tab', 'customers');
        
        return view('dashboard.admin', compact('totalUsers', 'totalCustomers', 'totalBookings', 'pendingBookings', 'pendingPayments', 'users', 'bookings', 'payments', 'muaProfile', 'portfolios', 'packages', 'addOns', 'activeTab'));
    }
}
