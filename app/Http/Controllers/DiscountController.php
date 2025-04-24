<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->user()->store;
        $discounts = $store->discounts()
            ->with('categories')
            ->latest()
            ->paginate(10);

        return view('seller.discount.index', compact('discounts'));
    }

    public function create(Request $request)
    {
        $store = $request->user()->store;

        $categories = Category::whereHas('products', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })->distinct()->get();

        return view('seller.discount.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $store = $request->user()->store;
    
        // Validasi yang diperbarui, termasuk 'type' dan 'value'
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|unique:discounts|max:50',
            'type' => 'required|in:percentage,fixed', 
            'value' => 'required|numeric|min:0', 
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'nullable|integer|min:1',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'is_active' => 'required|boolean',
            'max_uses' => 'required|integer|min:0',
        ]);
    
        // Menyimpan data discount dengan kategori yang terkait
        DB::transaction(function () use ($store, $validated, $request) {
            $discount = $store->discounts()->create($validated);
            $discount->categories()->sync($request->categories);
        });
    
        return redirect()->route('seller.discounts.index')
            ->with('success', 'Voucher berhasil dibuat');
    }
    

    public function edit(Request $request, $id)
    {
        $store = $request->user()->store;
        
        $discount = $store->discounts()
            ->with('categories')
            ->findOrFail($id);

        $categories = Category::whereHas('products', function($query) use ($store) {
            $query->where('store_id', $store->id);
        })->distinct()->get();

        return view('seller.discount.edit', compact('discount', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $store = $request->user()->store;
        
        $discount = $store->discounts()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|unique:discounts,code,'.$discount->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_uses' => 'nullable|integer|min:1',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'max_uses' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($discount, $validated, $request) {
            $discount->update($validated);
            $discount->categories()->sync($request->categories);
        });

        return redirect()->route('seller.discounts.index')
            ->with('success', 'Voucher berhasil diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        $store = $request->user()->store;
        
        $discount = $store->discounts()->findOrFail($id);
        
        try {
            DB::transaction(function () use ($discount) {
                $discount->categories()->detach();
                $discount->delete();
            });
            
            return redirect()->route('seller.discounts.index')
                ->with('success', 'Voucher berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus voucher: '.$e->getMessage());
        }
    }
}