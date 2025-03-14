<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of products for customers.
     */
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
            'sortOption' => $request->sort,
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $this->recordProductView($product);

        return view('products.show', [
            'title' => $product->name,
            'product' => $product->load('images', 'category', 'store'),
            'relatedProducts' => $this->getRelatedProducts($product),
            'carts' => $this->getUserCarts(),
            'categories' => Category::all(),
        ]);
    }

    /**
     * Display a listing of products for the seller.
     */
    public function sellerProducts()
    {
        $user = Auth::user();
        $store = $user->store;

        abort_unless($store, 403, 'Anda belum memiliki toko');

        // Get all products for the store
        $products = $store->products()->with(['category', 'images'])->get();
        
        // Calculate stats
        $stats = [
            'total' => $products->count(),
            'active' => $products->where('is_active', true)->count(),
            'low_stock' => $products->where('stock', '<=', 5)->where('stock', '>', 0)->count()
        ];

        return view('seller.products.index', [
            'store' => $store,
            'categories' => Category::all(),
            'products' => $products,
            'stats' => $stats
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $user = Auth::user();
        $store = $user->store;

        abort_unless($store, 403, 'Anda belum memiliki toko');

        return view('seller.products.create', [
            'store' => $store,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $store = Auth::user()->store;
        abort_unless($store, 403, 'Anda belum memiliki toko');

        $validator = Validator::make($request->all(), $this->validationRules(), $this->validationMessages());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create product with basic data
            $product = new Product($request->except('images'));
            $product->store_id = $store->id;
            $product->slug = Str::slug($request->name) . '-' . Str::random(5);
            $product->is_active = true;
            $product->save();

            // Handle image uploads
            $this->handleProductImages($request, $product);

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        return view('seller.products.edit', [
            'product' => $product->load('images'),
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validator = Validator::make($request->all(), $this->validationRules(), $this->validationMessages());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update product with basic data
            $product->fill($request->except('images'));
            
            // Update slug if name changed
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($request->name) . '-' . Str::random(5);
            }
            
            // Handle is_active checkbox
            $product->is_active = $request->has('is_active');
            
            $product->save();

            // Handle image uploads
            $this->handleProductImages($request, $product);

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        try {
            // Delete all product images
            $this->deleteProductImages($product);
            
            // Delete the product
            $product->delete();

            return redirect()->route('seller.products.index')
                ->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('seller.products.index')
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Upload a temporary image for the product.
     */
    public function uploadTempImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            $path = $request->file('file')->store('temp-images', 'public');
            return response()->json([
                'id' => uniqid(), // Generate a unique ID for the image
                'path' => $path,
                'url' => Storage::url($path),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal mengupload gambar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove a temporary image.
     */
    public function removeTempImage(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            // Validate path
            if (Storage::disk('public')->exists($request->path)) {
                Storage::disk('public')->delete($request->path);
                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'File tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a product image.
     */
    public function deleteImage($imageId)
    {
        try {
            $image = ProductImage::findOrFail($imageId);
            
            // Check authorization
            $this->authorize('update', $image->product);
            
            // Delete the file
            Storage::disk('public')->delete($image->path);
            
            // Delete the record
            $image->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal menghapus gambar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply filters to the product query.
     */
    private function applyFilters(Request $request, $query)
    {
        $request->whenFilled('category', function ($value) use ($query) {
            $query->where('category_id', $value);
        });

        $request->whenFilled('search', function ($value) use ($query) {
            $query->where('name', 'like', "%{$value}%");
        });
    }

    /**
     * Apply sorting to the product query.
     */
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

    /**
     * Get validation rules for product.
     */
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
        ];
    }

    /**
     * Get validation messages for product.
     */
    private function validationMessages()
    {
        return [
            'name.required' => 'Nama produk wajib diisi',
            'price.min' => 'Harga minimal Rp 1.000',
            'description.min' => 'Deskripsi minimal 20 karakter',
            'weight.min' => 'Berat minimal 100 gram',
        ];
    }

    /**
     * Handle product image uploads.
     */
    private function handleProductImages(Request $request, Product $product)
    {
        // Process images from temporary storage
        if ($request->has('images') && is_array($request->images)) {
            foreach ($request->images as $imagePath) {
                // Skip if not a valid path
                if (!Storage::disk('public')->exists($imagePath)) {
                    continue;
                }
                
                // Move from temp to permanent storage
                $newPath = 'product-images/' . basename($imagePath);
                Storage::disk('public')->move($imagePath, $newPath);
                
                // Create image record
                $product->images()->create([
                    'path' => $newPath,
                ]);
            }
        }
    }

    /**
     * Delete all images for a product.
     */
    private function deleteProductImages(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    /**
     * Get user's cart items.
     */
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

    /**
     * Get related products.
     */
    private function getRelatedProducts(Product $product)
    {
        return Product::with(['images'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();
    }

    /**
     * Record product view.
     */
    private function recordProductView(Product $product)
    {
        $product->increment('views');
        $product->category()->increment('views');
    }
}

