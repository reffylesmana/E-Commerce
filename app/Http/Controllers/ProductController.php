<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'category'])->where('is_active', true);

        $this->applyFilters($request, $query);
        $this->applySorting($request, $query);

        return view('products.index', [
            'title' => 'Katalog Produk',
            'categories' => Category::all(),
            'products' => $query->paginate(12),
            'carts' => $this->getUserCarts(),
            'selectedCategory' => $request->category,
            'searchQuery' => $request->search,
            'sortOption' => $request->sort
        ]);
    }

    public function show(Product $product)
    {
        $this->recordProductView($product);

        return view('products.show', [
            'title' => $product->name,
            'product' => $product->load('images', 'category'),
            'relatedProducts' => $this->getRelatedProducts($product),
            'carts' => $this->getUserCarts(),
            'categories' => Category::all()
        ]);
    }

    public function sellerProducts()
    {
        $store = Auth::user()->store;
        abort_unless($store, 403, 'Anda belum memiliki toko');

        return view('seller.products.index', [
            'products' => $store->products()
                ->with(['category', 'images'])
                ->latest()
                ->paginate(10)
        ]);
    }

    public function create()
    {
        $store = Auth::user()->store;
        abort_unless($store, 403, 'Anda belum memiliki toko');

        return view('seller.products.create', [
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        $store = Auth::user()->store;
        abort_unless($store, 403, 'Anda belum memiliki toko');

        $validator = Validator::make($request->all(), $this->validationRules(), $this->validationMessages());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam input data');
        }

        try {
            $product = $store->products()->create($request->except('images'));
            $this->handleImageUpload($request, $product);

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        return view('seller.products.edit', [
            'product' => $product->load('images'),
            'categories' => Category::all()
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validator = Validator::make($request->all(), $this->validationRules(), $this->validationMessages());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam input data');
        }

        try {
            $product->update($request->except('images'));
            $this->handleImageUpload($request, $product);

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        try {
            $this->deleteProductImages($product);
            $product->delete();

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240'
        ]);

        try {
            $path = $request->file('file')->store('temp-images', 'public');
            return response()->json([
                'path' => $path,
                'url' => Storage::disk('public')->url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengupload gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function data()
    {
        $store = Auth::user()->store;
        abort_unless($store, 403, 'Anda belum memiliki toko');

        return datatables()->eloquent(
            $store->products()
                ->with(['category', 'images'])
                ->select('products.*')
        )
            ->addColumn('action', function($product) {
                return view('seller.products.action', compact('product'))->render();
            })
            ->editColumn('price', function($product) {
                return 'Rp ' . number_format($product->price, 0, ',', '.');
            })
            ->editColumn('stock', function($product) {
                return $product->stock > 0 
                    ? '<span class="text-green-600">'.$product->stock.'</span>' 
                    : '<span class="text-red-600">Habis</span>';
            })
            ->rawColumns(['action', 'stock'])
            ->toJson();
    }

    // Helper Methods
    private function applyFilters(Request $request, $query)
    {
        $request->whenFilled('category', function ($value) use ($query) {
            $query->where('category_id', $value);
        });

        $request->whenFilled('search', function ($value) use ($query) {
            $query->where('name', 'like', "%{$value}%");
        });
    }

    private function applySorting(Request $request, $query)
    {
        $sort = $request->input('sort', 'latest');
        
        switch ($sort) {
            case 'latest':
                $query->latest();
                break;
            case 'cheapest':
                $query->orderBy('price');
                break;
            case 'expensive':
                $query->orderByDesc('price');
                break;
            case 'sold':
                $query->orderByDesc('sold');
                break;
        }
    }

    private function validationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'weight' => 'required|integer|min:100',
            'size' => 'nullable|string|max:50',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240'
        ];
    }

    private function validationMessages()
    {
        return [
            'name.required' => 'Nama produk wajib diisi',
            'price.min' => 'Harga minimal Rp 1.000',
            'description.min' => 'Deskripsi minimal 20 karakter',
            'weight.min' => 'Berat minimal 100 gram',
            'images.*.max' => 'Ukuran gambar maksimal 10MB'
        ];
    }

    private function handleImageUpload(Request $request, Product $product)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product-images', 'public');
                $product->images()->create(['path' => $path]);
            }
        }
    }

    private function deleteProductImages(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    private function getUserCarts()
    {
        return Auth::check() 
            ? Cart::with(['product.images'])
                ->where('user_id', Auth::id())
                ->latest()
                ->limit(5)
                ->get()
            : collect();
    }

    private function getRelatedProducts(Product $product)
    {
        return Product::with(['images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
    }

    private function recordProductView(Product $product)
    {
        $product->increment('views');
        $product->category()->increment('views');
    }
}