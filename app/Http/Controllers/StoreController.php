<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function index()
    {
        $store = Store::with('categories')
            ->where('user_id', Auth::id())
            ->first();
            
        $categories = Category::all();
        return view('seller.store', compact('store', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'alamat' => 'nullable|string', 
            'logo' => 'required|image|mimes:jpeg,png,gif|max:1024',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ], [
            'name.required' => 'Nama toko wajib diisi',
            'description.required' => 'Deskripsi toko wajib diisi',
            'alamat.required' => 'Alamat toko wajib diisi',
            'logo.required' => 'Logo toko wajib diupload',
            'categories.required' => 'Pilih minimal 1 kategori',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('swal', [
                    'title' => 'Validasi Gagal',
                    'text' => 'Terdapat kesalahan pada inputan',
                    'icon' => 'error',
                    'timer' => 10000
                ]);
        }

        try {
            $store = new Store();
            $store->name = $request->name;
            $store->slug = Str::slug($request->name);
            $store->alamat = $request->alamat;
            $store->description = $request->description;
            $store->user_id = Auth::id();
            $store->created_by = Auth::id(); 
            $store->is_active = false;

            // Upload logo
            $logo = $request->file('logo');
            $filename = time() . '_' . Str::slug($store->name) . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('store-logos', $filename, 'public');
            $store->logo = $path;

            $store->save();

            // Attach categories
            $store->categories()->attach($request->categories);

            return redirect()->route('seller.store.index')->with('swal', [
                'title' => 'Berhasil!',
                'text' => 'Toko berhasil dibuat. Menunggu persetujuan admin.',
                'icon' => 'success',
                'timer' => 5000
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('swal', [
                'title' => 'Gagal!',
                'text' => 'Gagal membuat toko: ' . $e->getMessage(),
                'icon' => 'error',
                'timer' => 10000
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $store = Store::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'alamat' => 'nullable|string', 
            'logo' => 'nullable|image|mimes:jpeg,png,gif|max:1024',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ], [
            'name.required' => 'Nama toko wajib diisi',
            'alamat.required' => 'Alamat toko wajib diisi',
            'description.required' => 'Deskripsi toko wajib diisi',
            'categories.required' => 'Pilih minimal 1 kategori',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('swal', [
                    'title' => 'Validasi Gagal',
                    'text' => 'Terdapat kesalahan pada inputan',
                    'icon' => 'error',
                    'timer' => 10000
                ]);
        }

        try {
            $store->name = $request->name;
            $store->alamat = $request->alamat;
            
            if (!$store->is_approved) {
                $store->slug = Str::slug($request->name);
            }
            
            $store->description = $request->description;

            // Update logo jika ada file baru
            if ($request->hasFile('logo')) {
                // Hapus logo lama
                if ($store->logo && Storage::disk('public')->exists($store->logo)) {
                    Storage::disk('public')->delete($store->logo);
                }
                
                // Upload logo baru
                $logo = $request->file('logo');
                $filename = time() . '_' . Str::slug($store->name) . '.' . $logo->getClientOriginalExtension();
                $path = $logo->storeAs('store-logos', $filename, 'public');
                $store->logo = $path;
            }

            $store->save();

            // Sync kategori
            $store->categories()->sync($request->categories);

            return redirect()->back()->with('swal', [
                'title' => 'Diperbarui!',
                'text' => 'Detail toko berhasil diperbarui',
                'icon' => 'success',
                'timer' => 3000
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('swal', [
                'title' => 'Gagal!',
                'text' => 'Gagal memperbarui toko: ' . $e->getMessage(),
                'icon' => 'error',
                'timer' => 10000
            ]);
        }
    }
}