<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Booking;
use App\Payment;
use App\MuaProfile;
use App\MuaPortfolio;
use App\MakeupPackage;
use App\PackageAddOn;
use App\AddOn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Show users management.
     */
    public function users(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Delete customer.
     */
    public function deleteUser($id)
    {
        $user = User::where('role', 'customer')->findOrFail($id);
        
        // Deletion will cascade to bookings and reviews due to DB constraints
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Customer berhasil dihapus.');
    }

    /**
     * Show bookings management.
     */
    public function bookings(Request $request)
    {
        $query = Booking::with(['customer', 'package', 'payment']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings', compact('bookings'));
    }

    /**
     * Show payments management.
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['booking.customer', 'verifier']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.payments', compact('payments'));
    }

    /**
     * Show WINWIN Makeup profile management.
     */
    public function profile()
    {
        $muaProfile = MuaProfile::getWinwinProfile();
        return view('admin.profile', compact('muaProfile'));
    }

    /**
     * Update WINWIN Makeup profile.
     */
    public function updateProfile(Request $request)
    {
        $muaProfile = MuaProfile::getWinwinProfile();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'experience_years' => 'nullable|integer|min:0',
            'specialization' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'login_background' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo from public/storage
            if ($muaProfile->cover_photo) {
                $oldCoverPath = public_path('storage/' . $muaProfile->cover_photo);
                if (file_exists($oldCoverPath)) {
                    unlink($oldCoverPath);
                }
            }
            
            // Upload new cover photo to public/storage
            $file = $request->file('cover_photo');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = public_path('storage/cover_photos');
            
            // Create directory if not exists
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $file->move($directory, $fileName);
            $validated['cover_photo'] = 'cover_photos/' . $fileName;
        }

        if ($request->hasFile('hero_image')) {
            // Delete old hero image from public/storage
            if ($muaProfile->hero_image) {
                $oldHeroPath = public_path('storage/' . $muaProfile->hero_image);
                if (file_exists($oldHeroPath)) {
                    unlink($oldHeroPath);
                }
            }

            // Upload new hero image to public/storage
            $file = $request->file('hero_image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = public_path('storage/hero_images');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $file->move($directory, $fileName);
            $validated['hero_image'] = 'hero_images/' . $fileName;
        }

        if ($request->hasFile('login_background')) {
            // Delete old login background from public/storage
            if ($muaProfile->login_background) {
                $oldLoginPath = public_path('storage/' . $muaProfile->login_background);
                if (file_exists($oldLoginPath)) {
                    unlink($oldLoginPath);
                }
            }

            // Upload new login background to public/storage
            $file = $request->file('login_background');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = public_path('storage/login_backgrounds');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $file->move($directory, $fileName);
            $validated['login_background'] = 'login_backgrounds/' . $fileName;
        }

        $muaProfile->update($validated);

        return redirect()->route('dashboard', ['tab' => 'profile'])->with('success', 'Profil WINWIN Makeup berhasil diupdate.');
    }

    /**
     * Show portfolio management.
     */
    public function portfolio()
    {
        $muaProfile = MuaProfile::getWinwinProfile();
        $portfolios = $muaProfile->portfolios()->orderBy('order')->get();

        return view('admin.portfolio', compact('portfolios', 'muaProfile'));
    }

    /**
     * Store portfolio.
     */
    public function storePortfolio(Request $request)
    {
        $muaProfile = MuaProfile::getWinwinProfile();

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
            'is_featured' => 'boolean',
        ]);

        $validated['mua_profile_id'] = $muaProfile->id;
        
        // Set default order if not provided
        if (!isset($validated['order'])) {
            $maxOrder = $muaProfile->portfolios()->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }
        
        // Upload image to public/storage
        $file = $request->file('image');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $directory = public_path('storage/portfolios');
        
        // Create directory if not exists
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $file->move($directory, $fileName);
        $validated['image'] = 'portfolios/' . $fileName;

        MuaPortfolio::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Portfolio berhasil ditambahkan.', 'tab' => 'portfolio']);
        }

        return redirect()->route('dashboard', ['tab' => 'portfolio'])->with('success', 'Portfolio berhasil ditambahkan.');
    }

    /**
     * Update portfolio.
     */
    public function updatePortfolio(Request $request, $id)
    {
        $portfolio = MuaPortfolio::findOrFail($id);

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer',
            'is_featured' => 'boolean',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image
            $oldImagePath = public_path('storage/' . $portfolio->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Upload new image
            $file = $request->file('image');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $directory = public_path('storage/portfolios');
            
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $file->move($directory, $fileName);
            $validated['image'] = 'portfolios/' . $fileName;
        }

        $portfolio->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Portfolio berhasil diupdate.', 'tab' => 'portfolio']);
        }

        return redirect()->route('dashboard', ['tab' => 'portfolio'])->with('success', 'Portfolio berhasil diupdate.');
    }

    /**
     * Delete portfolio.
     */
    public function deletePortfolio($id)
    {
        $portfolio = MuaPortfolio::findOrFail($id);
        // Delete from public/storage
        $imagePath = public_path('storage/' . $portfolio->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $portfolio->delete();

        return redirect()->route('dashboard', ['tab' => 'portfolio'])->with('success', 'Portfolio berhasil dihapus.');
    }

    /**
     * Show packages management.
     */
    public function packages()
    {
        $muaProfile = MuaProfile::getWinwinProfile();
        $packages = $muaProfile->packages()->orderBy('order')->get();

        return view('admin.packages', compact('packages', 'muaProfile'));
    }

    /**
     * Store package.
     */
    public function storePackage(Request $request)
    {
        $muaProfile = MuaProfile::getWinwinProfile();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'includes' => 'nullable|string|max:1000',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['mua_profile_id'] = $muaProfile->id;

        // Handle images upload to public/storage
        if ($request->hasFile('images')) {
            $imagePaths = [];
            $directory = public_path('storage/package_images');
            
            // Create directory if not exists
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            foreach ($request->file('images') as $image) {
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($directory, $fileName);
                $imagePaths[] = 'package_images/' . $fileName;
            }
            $validated['images'] = $imagePaths;
        }

        MakeupPackage::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Paket layanan baru telah di buat!', 'tab' => 'packages']);
        }

        return redirect()->route('dashboard', ['tab' => 'packages'])->with('success', 'Paket layanan baru telah di buat!');
    }

    /**
     * Update package.
     */
    public function updatePackage(Request $request, $id)
    {
        $package = MakeupPackage::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'includes' => 'nullable|string|max:1000',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle images upload to public/storage
        if ($request->hasFile('images')) {
            $existingImages = $package->images ?? [];
            $newImagePaths = [];
            $directory = public_path('storage/package_images');
            
            // Create directory if not exists
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            foreach ($request->file('images') as $image) {
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($directory, $fileName);
                $newImagePaths[] = 'package_images/' . $fileName;
            }
            $validated['images'] = array_merge($existingImages, $newImagePaths);
        }

        $package->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Paket layanan berhasil diperbarui!', 'tab' => 'packages']);
        }

        return redirect()->route('dashboard', ['tab' => 'packages'])->with('success', 'Paket layanan berhasil diperbarui!');
    }

    /**
     * Delete package.
     */
    public function deletePackage($id)
    {
        $package = MakeupPackage::findOrFail($id);
        $package->delete();

        return redirect()->route('dashboard', ['tab' => 'packages'])->with('success', 'Paket berhasil dihapus.');
    }

    /**
     * Store add-on for a package.
     */
    public function storeAddOn(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:makeup_packages,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        // Set default order if not provided
        if (!isset($validated['order'])) {
            $maxOrder = PackageAddOn::where('package_id', $validated['package_id'])->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        $addOn = PackageAddOn::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Add-on berhasil ditambahkan.',
                'add_on' => $addOn
            ]);
        }

        return redirect()->back()->with('success', 'Add-on berhasil ditambahkan.');
    }

    /**
     * Update add-on.
     */
    public function updateAddOn(Request $request, $id)
    {
        $addOn = PackageAddOn::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $addOn->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Add-on berhasil diupdate.',
                'add_on' => $addOn->fresh()
            ]);
        }

        return redirect()->back()->with('success', 'Add-on berhasil diupdate.');
    }

    /**
     * Delete add-on.
     */
    public function deleteAddOn($id)
    {
        $addOn = PackageAddOn::findOrFail($id);
        $addOn->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Add-on berhasil dihapus.'
            ]);
        }

        return redirect()->back()->with('success', 'Add-on berhasil dihapus.');
    }
    /**
     * Show global add-ons management.
     */
    public function addons()
    {
        $addOns = AddOn::orderBy('name')->get();
        return view('admin.addons', compact('addOns'));
    }

    /**
     * Store global add-on.
     */
    public function storeGlobalAddOn(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'default_price' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $addOn = AddOn::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Add-on berhasil ditambahkan.',
                'add_on' => $addOn
            ]);
        }

        return redirect()->route('dashboard', ['tab' => 'addons'])->with('success', 'Add-on berhasil ditambahkan.');
    }

    /**
     * Update global add-on.
     */
    public function updateGlobalAddOn(Request $request, $id)
    {
        $addOn = AddOn::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'default_price' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $addOn->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Add-on berhasil diupdate.',
                'add_on' => $addOn->fresh()
            ]);
        }

        return redirect()->route('dashboard', ['tab' => 'addons'])->with('success', 'Add-on berhasil diupdate.');
    }

    /**
     * Delete global add-on.
     */
    public function deleteGlobalAddOn($id)
    {
        $addOn = AddOn::findOrFail($id);
        $addOn->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Add-on berhasil dihapus.'
            ]);
        }

        return redirect()->route('dashboard', ['tab' => 'addons'])->with('success', 'Add-on berhasil dihapus.');
    }
}
