<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        // Get all reviews by the authenticated user
        $reviews = Review::where('user_id', Auth::id())
            ->with('product.photos')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get products that the user has purchased but not reviewed yet
        $purchasedProductIds = OrderItem::whereHas('order', function($query) {
            $query->where('user_id', Auth::id())
                  ->where('status', 'completed');
        })->pluck('product_id')->unique();
        
        $reviewedProductIds = Review::where('user_id', Auth::id())
            ->pluck('product_id');
        
        $productsToReview = Product::whereIn('id', $purchasedProductIds)
            ->whereNotIn('id', $reviewedProductIds)
            ->with('photos')
            ->get();

        return view('reviews.index', compact('reviews', 'productsToReview'));
    }

    public function create(Product $product)
    {
        // Check if user has purchased this product
        $hasPurchased = OrderItem::whereHas('order', function($query) {
            $query->where('user_id', Auth::id())
                  ->where('status', 'completed');
        })->where('product_id', $product->id)->exists();

        if (!$hasPurchased) {
            return redirect()->route('reviews.index')->with('error', 'Anda hanya dapat memberikan ulasan untuk produk yang telah Anda beli.');
        }

        // Check if user has already reviewed this product
        $hasReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        if ($hasReviewed) {
            return redirect()->route('reviews.index')->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        return view('reviews.create', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'star' => 'required|integer|min:1|max:5',
            'text' => 'required|string|min:10|max:500',
        ]);

        // Check if user has purchased this product
        $hasPurchased = OrderItem::whereHas('order', function($query) {
            $query->where('user_id', Auth::id())
                  ->where('status', 'completed');
        })->where('product_id', $request->product_id)->exists();

        if (!$hasPurchased) {
            return redirect()->route('reviews.index')->with('error', 'Anda hanya dapat memberikan ulasan untuk produk yang telah Anda beli.');
        }

        // Check if user has already reviewed this product
        $hasReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($hasReviewed) {
            return redirect()->route('reviews.index')->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        // Create the review
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'star' => $request->star,
            'text' => $request->text,
        ]);

        return redirect()->route('reviews.index')->with('success', 'Ulasan berhasil ditambahkan.');
    }

    public function edit(Review $review)
    {
        // Verify the review belongs to the authenticated user
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki akses untuk mengedit ulasan ini.');
        }

        $product = $review->product;

        return view('reviews.edit', compact('review', 'product'));
    }

    public function update(Request $request, Review $review)
    {
        // Verify the review belongs to the authenticated user
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki akses untuk mengedit ulasan ini.');
        }

        $request->validate([
            'star' => 'required|integer|min:1|max:5',
            'text' => 'required|string|min:10|max:500',
        ]);

        // Update the review
        $review->update([
            'star' => $request->star,
            'text' => $request->text,
        ]);

        return redirect()->route('reviews.index')->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroy(Review $review)
    {
        // Verify the review belongs to the authenticated user
        if ($review->user_id !== Auth::id()) {
            return redirect()->route('reviews.index')->with('error', 'Anda tidak memiliki akses untuk menghapus ulasan ini.');
        }

        // Delete the review
        $review->delete();

        return redirect()->route('reviews.index')->with('success', 'Ulasan berhasil dihapus.');
    }
}