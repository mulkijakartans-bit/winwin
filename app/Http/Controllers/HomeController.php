<?php

namespace App\Http\Controllers;

use App\MuaProfile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        // Redirect to dashboard if user is logged in
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        $muaProfile = MuaProfile::getWinwinProfile();
        
        $featuredPortfolios = $muaProfile->portfolios()
            ->where('is_featured', true)
            ->orderBy('order')
            ->limit(9)
            ->get();

        $packages = $muaProfile->packages()
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(3)
            ->get();

        return view('home', compact('muaProfile', 'featuredPortfolios', 'packages'));
    }
}
