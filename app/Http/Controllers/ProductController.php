<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of products for customers (12 featured products).
     */
    public function index(Request $request)
    {
        $query = Product::with(['photos', 'category', 'reviews'])
                        ->where('is_active', true);
    
        $this->applyFilters($request, $query);
        $this->applySorting($request, $query);
    
        $products = $query->paginate(12);
    
        return view('index', [
            'title' => 'Katalog Produk',
            'categories' => Category::all(),
            'products' => $products,
            'carts' => $this->getUserCarts(),
            'selectedCategory' => $request->category,
            'searchQuery' => $request->search,
            'sortOption' => $request->sort,
        ]);
    }

    /**
     * Display a listing of all products.
     */
    public function allProducts(Request $request)
    {
        $query = Product::with(['photos', 'category'])->where('is_active', true)->withAvg('reviews', 'rating');

        $this->applyFilters($request, $query);
        $this->applySorting($request, $query);
        

        return view('products', [
            'title' => 'Semua Produk',
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
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->increment('views');
    
        $reviews = Review::with(['user'])->where('product_id', $product->id)->latest()->get();
    
        $averageRating = $reviews->avg('rating'); 
        $averageRating = $averageRating ? $averageRating : 0; 
    
        $category = Category::findOrFail($product->category_id);
        $store = Store::findOrFail($product->store_id);
        $photos = ProductPhoto::where('product_id', $product->id)->get();
    
        // Ambil produk terkait
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();
    
        foreach ($relatedProducts as $relatedProduct) {
            $relatedProduct->photos = ProductPhoto::where('product_id', $relatedProduct->id)->get();
            $relatedProduct->averageRating = Review::where('product_id', $relatedProduct->id)->avg('rating');
            // Set default to 0 if no ratings
            $relatedProduct->averageRating = $relatedProduct->averageRating ? $relatedProduct->averageRating : 0;
        }
    
        return view('show', compact('product', 'category', 'store', 'photos', 'relatedProducts', 'reviews', 'averageRating'));
    }
    
    

    /**
     * Display a listing of products for the seller.
     */
    public function sellerProducts()
    {
        $user = Auth::user();
        $store = $user->store;
    
        abort_unless($store, 403, 'Anda belum memiliki toko');
    
        // Ambil semua produk dari toko
        $products = $store->products()->with(['category', 'photos'])->get();
    
        // Ambil semua order yang statusnya completed
        $completedOrders = Order::where('status', 'completed')->with('products')->get();
    
        // Hitung jumlah produk terjual
        $soldCounts = [];
        foreach ($completedOrders as $order) {
            foreach ($order->products as $orderItem) {
                $productId = $orderItem->product_id; // Ambil ID produk dari order_item
                if (isset($soldCounts[$productId])) {
                    $soldCounts[$productId] += $orderItem->quantity; // Tambahkan quantity
                } else {
                    $soldCounts[$productId] = $orderItem->quantity; // Inisialisasi quantity
                }
            }
        }
    
        // Tambahkan jumlah terjual ke setiap produk
        foreach ($products as $product) {
            $product->sold = $soldCounts[$product->id] ?? 0; // Jika tidak ada, set ke 0
        }
    
        $stats = [
            'total' => $products->count(),
            'active' => $products->where('is_active', true)->count(),
            'low_stock' => $products->where('stock', '<=', 5)->where('stock', '>', 0)->count(),
        ];
    
        return view('seller.products.index', [
            'store' => $store,
            'categories' => Category::all(),
            'products' => $products,
            'stats' => $stats,
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
            'categories' => $store->categories,
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
            $errors = $validator->errors();

            // Determine which section has errors
            $section = 1; // Default to section 1

            if ($errors->has('price') || $errors->has('stock') || $errors->has('weight') || $errors->has('description')) {
                $section = 2;
            } elseif ($errors->has('photos')) {
                $section = 3;
            }

            // Store the section in session flash data
            session()->flash('error_section', $section);

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Create product with basic data
            $product = new Product($request->except('photos'));
            $product->store_id = $store->id;
            $product->user_id = Auth::id(); // Make sure user_id is set
            $product->slug = Str::slug($request->name) . '-' . Str::random(5);
            $product->is_active = true;
            $product->save();

            // Handle image uploads
            $this->handleProductImages($request, $product);

            // Show success message with options
            return redirect()->route('seller.products.index')->with('success', 'Produk berhasil disimpan')->with('show_options', true); // Flag to show "create another" option
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function storeProducts($slug)
    {
        // Temukan toko berdasarkan slug
        $store = Store::where('slug', $slug)->firstOrFail();

        $products = $store->products()
            ->with('category', 'reviews') 
            ->where('is_active', true)
            ->paginate(12);

        return view('store', [
            'store' => $store,
            'products' => $products,
        ]);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $store = $product->store;
        $categories = $store->categories;
    
        return view('seller.products.edit', [
            'product' => $product->load('photos'),
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), $this->validationRules(), $this->validationMessages());

        if ($validator->fails()) {
            $errors = $validator->errors();

            // Determine which section has errors
            $section = 1; // Default to section 1

            if ($errors->has('price') || $errors->has('stock') || $errors->has('weight') || $errors->has('description')) {
                $section = 2;
            } elseif ($errors->has('photos')) {
                $section = 3;
            }

            // Store the section in session flash data
            session()->flash('error_section', $section);

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Update product with basic data
            $product->fill($request->except('photos'));

            // Update slug if name changed
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($request->name) . '-' . Str::random(5);
            }

            // Handle is_active checkbox
            $product->is_active = $request->has('is_active');

            $product->save();

            // Handle image uploads
            $this->handleProductImages($request, $product);

            return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Autorisasi manual
        $user = Auth::user();
        $store = $user->store;
        
        // Cek kepemilikan toko
        if (!$store || $store->id !== $product->store_id) {
            abort(403, 'This action is unauthorized.');
        }

        try {
            $this->deleteProductImages($product);
            $product->delete();

            return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->route('seller.products.index')
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Upload a temporary image for the product.
     */
    public function uploadTempImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()->first('file'),
                ],
                422,
            );
        }

        try {
            $path = $request->file('file')->store('product-photos', 'public');
            return response()->json([
                'id' => uniqid(),
                'path' => $path,
                'url' => Storage::url($path),
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => 'Gagal mengupload gambar: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Remove a temporary image.
     */
    public function removeTempImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'error' => $validator->errors()->first('path'),
                ],
                422,
            );
        }

        try {
            if (Storage::disk('public')->exists($request->path)) {
                Storage::disk('public')->delete($request->path);
                return response()->json(['success' => true]);
            }

            return response()->json(
                [
                    'success' => false,
                    'error' => 'File tidak ditemukan',
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => 'Gagal menghapus file: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Delete a product image.
     */
    public function deleteImage($imageId)
    {
        try {
            $image = ProductPhoto::findOrFail($imageId);
            $user = Auth::user();
            $store = $user->store;
            
            // Cek kepemilikan toko
            if (!$store || $store->id !== $image->product->store_id) {
                abort(403, 'This action is unauthorized.');
            }

            Storage::disk('public')->delete($image->photo);
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'error' => 'Gagal menghapus gambar: ' . $e->getMessage(),
                ],
                500,
            );
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
            'category_id' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    $store = Auth::user()->store;
                    if (!$store->categories->contains($value)) {
                        $fail('Kategori yang dipilih tidak valid untuk toko ini.');
                    }
                },
            ],
            'description' => 'required|string|min:20',
            'weight' => 'required|numeric',
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
        ];
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

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
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
     * Handle product image uploads.
     */
    private function handleProductImages(Request $request, Product $product)
    {
        if ($request->hasFile('photos') && is_array($request->file('photos'))) {
            foreach ($request->file('photos') as $image) {
                if ($image->isValid()) {
                    // Simpan gambar ke storage dan dapatkan path
                    $path = $image->store('product-photos', 'public'); // Pastikan ini adalah 'product-photos'
    
                    // Simpan informasi gambar ke tabel product_photos
                    $product->photos()->create([
                        'photo' => $path,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    Log::error('Invalid image file: ' . $image->getError());
                }
            }
        } else {
            Log::warning('No photos uploaded or invalid input.');
        }
    }

    /**
     * Delete all images for a product.
     */
    private function deleteProductImages(Product $product)
    {
        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->photo);
            $photo->delete();
        }
    }

    /**
     * Get user's cart items.
     */
    private function getUserCarts()
    {
        return Auth::check()
            ? Cart::with(['product.photos'])
                ->where('user_id', Auth::id())
                ->latest()
                ->limit(5)
                ->get()
            : collect();
    }

    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => auth::id(),
            'product_id' => $request->product_id,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke wishlist.');
    }

    public function removeFromWishlist($id)
    {
        $wishlist = Wishlist::where('user_id', auth::id())->where('product_id', $id)->first();
        if ($wishlist) {
            $wishlist->delete();
            return redirect()->back()->with('success', 'Produk berhasil dihapus dari wishlist.');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan di wishlist.');
    }
}
