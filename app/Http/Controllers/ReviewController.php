<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * Show the form for creating a new review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $order = Order::with(['orderItems.product'])
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
            return redirect()->route('orders.completed')
                ->with('error', 'Anda sudah memberikan penilaian untuk semua produk dalam pesanan ini.');
        }
        
        return view('create-review', compact('order'));
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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
                continue; // Skip if review already exists
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
                    $image->storeAs('public/reviews', $imageName);
                    $imageNames[] = $imageName;
                }
                
                $review->images = $imageNames;
                $review->save();
            }
            
            // Update product rating
            $product = Product::find($reviewData['product_id']);
            $productReviews = Review::where('product_id', $product->id)->get();
            $avgRating = $productReviews->avg('rating');
            $product->rating = $avgRating;
            $product->save();
        }
        
        return redirect()->route('orders.completed')
            ->with('success', 'Terima kasih! Penilaian Anda telah berhasil disimpan.');
    }
}
