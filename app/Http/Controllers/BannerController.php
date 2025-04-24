<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage banners
        if (!$store || !$store->canManageBanners()) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengelola banner.');
        }

        $banners = Banner::where('store_id', $store->id)
            ->orderBy('order')
            ->get();

        return view('seller.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage banners
        if (!$store || !$store->canManageBanners()) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengelola banner.');
        }

        return view('seller.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $store = $user->store;
    
        if (!$store || !$store->canManageBanners()) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengelola banner.');
        }
    
        // Cek jumlah banner aktif untuk store ini
        $activeBannerCount = Banner::where('store_id', $store->id)
            ->where('is_active', true)
            ->count();
    
        if ($activeBannerCount >= 3) {
            return redirect()->route('seller.banners.index')
                ->with('error', 'Anda hanya dapat memiliki maksimal 3 banner aktif.');
        }
    
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
    
        $imagePath = $request->file('image')->store('banners', 'public');
    
        $banner = new Banner([
            'store_id' => $store->id,
            'title' => $request->title,
            'image_path' => $imagePath,
            'link_url' => $request->link_url,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    
        $banner->save();
    
        return redirect()->route('seller.banners.index')
            ->with('success', 'Banner berhasil ditambahkan.');
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage banners and owns this banner
        if (!$store || !$store->canManageBanners() || $banner->store_id !== $store->id) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit banner ini.');
        }

        return view('seller.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage banners and owns this banner
        if (!$store || !$store->canManageBanners() || $banner->store_id !== $store->id) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit banner ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Update image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }

            // Upload new image
            $imagePath = $request->file('image')->store('banners', 'public');
            $banner->image_path = $imagePath;
        }

        // Update banner
        $banner->title = $request->title;
        $banner->link_url = $request->link_url;
        $banner->description = $request->description;
        $banner->order = $request->order ?? 0;
        $banner->is_active = $request->has('is_active');
        $banner->start_date = $request->start_date;
        $banner->end_date = $request->end_date;

        $banner->save();

        return redirect()->route('seller.banners.index')
            ->with('success', 'Banner berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage banners and owns this banner
        if (!$store || !$store->canManageBanners() || $banner->store_id !== $store->id) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus banner ini.');
        }

        // Delete image
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }

        // Delete banner
        $banner->delete();

        return redirect()->route('seller.banners.index')
            ->with('success', 'Banner berhasil dihapus.');
    }

    /**
     * Update the order of banners.
     */
    public function updateOrder(Request $request)
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage banners
        if (!$store || !$store->canManageBanners()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'banners' => 'required|array',
            'banners.*.id' => 'required|exists:banners,id',
            'banners.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->banners as $item) {
            $banner = Banner::find($item['id']);
            
            // Check if banner belongs to this store
            if ($banner && $banner->store_id === $store->id) {
                $banner->order = $item['order'];
                $banner->save();
            }
        }

        return response()->json(['success' => true]);
    }
}
