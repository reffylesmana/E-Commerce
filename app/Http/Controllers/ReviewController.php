<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * Display a listing of the user's reviews.
     */
    public function index()
    {
        $reviews = Review::with(['product.photos', 'order'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        foreach ($reviews as $review) {
            $review->images = json_decode($review->images, true); 
        }
            
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Request $request)
    {
        $order = Order::with(['orderItems.product.photos'])
            ->where('user_id', Auth::id())
            ->where('id', $request->order_id)
            ->where('status', 'completed')
            ->firstOrFail();
            
        // Check if all items in this order already have reviews
        $allReviewed = true;
        foreach ($order->orderItems as $item) {
            $review = Review::where('order_id', $order->id)
                ->where('product_id', $item->product_id)
                ->where('order_item_id', $item->id)
                ->first();
                
            if (!$review) {
                $allReviewed = false;
                break;
            }
        }
        
        if ($allReviewed) {
            return redirect()->route('reviews.index')
                ->with('info', 'Anda sudah memberikan penilaian untuk semua produk dalam pesanan ini.');
        }
        
        return view('reviews.create', compact('order'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $request->order_id)
            ->where('status', 'completed')
            ->firstOrFail();
            
        $reviews = $request->reviews;
        
        foreach ($reviews as $key => $reviewData) {
            // Validate each review
            $request->validate([
                "reviews.{$key}.product_id" => 'required|exists:products,id',
                "reviews.{$key}.order_item_id" => 'required|exists:order_items,id',
                "reviews.{$key}.rating" => 'required|integer|min:1|max:5',
                "reviews.{$key}.comment" => 'required|string|min:5|max:1000',
                "reviews.{$key}.images.*" => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            
            // Check if review already exists
            $existingReview = Review::where('order_id', $order->id)
                ->where('product_id', $reviewData['product_id'])
                ->where('order_item_id', $reviewData['order_item_id'])
                ->first();
                
            if ($existingReview) {
                continue;
            }
            
            // Create new review
            $review = new Review();
            $review->user_id = Auth::id();
            $review->order_id = $order->id;
            $review->product_id = $reviewData['product_id'];
            $review->order_item_id = $reviewData['order_item_id'];
            $review->rating = $reviewData['rating'];
            $review->comment = $reviewData['comment'];
            
            // Save review to get ID
            $review->save();
            
            // Handle images
            if (isset($reviewData['images']) && !empty($reviewData['images'])) {
                $imageNames = [];
                
                foreach ($reviewData['images'] as $image) {
                    $imageName = 'review_' . $review->id . '_' . time() . '_' . uniqid() . '.' . $image->extension();
                    $image->storeAs('reviews', $imageName, 'public');
                    $imageNames[] = $imageName;
                }
                
                $review->images = json_encode($imageNames);
                $review->save();
            }
            
            // Create notification for seller
            $product = Product::find($review->product_id);
            if ($product && $product->user_id) {
                DB::table('notifications')->insert([
                    'id' => Str::uuid(),
                    'type' => 'App\Notifications\NewReview',
                    'notifiable_id' => $product->user_id,
                    'notifiable_type' => 'App\Models\User',
                    'data' => json_encode([
                        'message' => 'Produk Anda "' . $product->name . '" menerima penilaian baru',
                        'product_id' => $product->id,
                        'review_id' => $review->id,
                        'rating' => $review->rating,
                        'buyer_id' => Auth::id(),
                        'created_at' => now()->toDateTimeString()
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        return redirect()->route('reviews.index')
            ->with('success', 'Terima kasih! Penilaian Anda telah berhasil disimpan.');
    }
    
    /**
     * Show the form for editing the specified review.
     */
    public function edit($id)
    {
        $review = Review::with(['product.photos', 'order'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $review->images = json_decode($review->images, true) ?? [];
            
        return view('reviews.edit', compact('review'));
    }
    
    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        
        // Handle images
        $imageNames = json_decode($review->images, true) ?? [];
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = 'review_' . $review->id . '_' . time() . '_' . uniqid() . '.' . $image->extension();
                $image->storeAs('reviews', $imageName, 'public');
                $imageNames[] = $imageName;
            }
        }
        
        $review->images = json_encode($imageNames);
        $review->save();
        
        // Create notification for seller about updated review
        $product = Product::find($review->product_id);
        if ($product && $product->user_id) {
            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => 'App\Notifications\ReviewUpdated',
                'notifiable_id' => $product->user_id,
                'notifiable_type' => 'App\Models\User',
                'data' => json_encode([
                    'message' => 'Penilaian untuk produk "' . $product->name . '" telah diperbarui',
                    'product_id' => $product->id,
                    'review_id' => $review->id,
                    'rating' => $review->rating,
                    'buyer_id' => Auth::id(),
                    'created_at' => now()->toDateTimeString()
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        return redirect()->route('reviews.index')
            ->with('success', 'Penilaian berhasil diperbarui.');
    }
    
    /**
     * Remove an image from a review.
     */
    public function removeImage(Request $request, $id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $request->validate([
            'image' => 'required|string',
        ]);
        
        $imageName = $request->image;
        $images = json_decode($review->images, true) ?? [];
        
        if (in_array($imageName, $images)) {
            // Remove from storage
            Storage::disk('public')->delete('reviews/' . $imageName);
            
            // Remove from array
            $images = array_diff($images, [$imageName]);
            $review->images = json_encode(array_values($images));
            $review->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus.'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Foto tidak ditemukan.'
        ], 404);
    }
}