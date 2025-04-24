<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerBlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage blogs
        if (!$store || !$store->canManageBlogs()) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengelola blog.');
        }

        $blogs = Blog::where('store_id', $store->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('seller.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage blogs
        if (!$store || !$store->canManageBlogs()) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengelola blog.');
        }

        return view('seller.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage blogs
        if (!$store || !$store->canManageBlogs()) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengelola blog.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs',
            'featured_image' => 'nullable|image|max:2048',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Generate slug if not provided
        $slug = $request->slug ?? Str::slug($request->title);

        // Upload featured image if provided
        $featuredImage = null;
        if ($request->hasFile('featured_image')) {
            $featuredImage = $request->file('featured_image')->store('blogs', 'public');
        }

        // Set published_at if is_published is true and published_at is not provided
        $publishedAt = $request->published_at;
        if ($request->has('is_published') && !$publishedAt) {
            $publishedAt = now();
        }

        // Create blog
        $blog = new Blog([
            'store_id' => $store->id,
            'title' => $request->title,
            'slug' => $slug,
            'featured_image' => $featuredImage,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'is_published' => $request->has('is_published'),
            'published_at' => $publishedAt,
        ]);

        $blog->save();

        return redirect()->route('seller.blogs.index')
            ->with('success', 'Blog berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage blogs and owns this blog
        if (!$store || !$store->canManageBlogs() || $blog->store_id !== $store->id) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk melihat blog ini.');
        }

        return view('seller.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage blogs and owns this blog
        if (!$store || !$store->canManageBlogs() || $blog->store_id !== $store->id) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit blog ini.');
        }

        return view('seller.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage blogs and owns this blog
        if (!$store || !$store->canManageBlogs() || $blog->store_id !== $store->id) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit blog ini.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'featured_image' => 'nullable|image',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Generate slug if not provided
        if ($request->has('title') && (!$request->has('slug') || empty($request->slug))) {
            $blog->slug = Str::slug($request->title);
        } else {
            $blog->slug = $request->slug;
        }

        // Upload featured image if provided
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            // Upload new image
            $blog->featured_image = $request->file('featured_image')->store('blogs', 'public');
        }

        // Set published_at if is_published is true and published_at is not provided
        if ($request->has('is_published') && !$request->published_at && !$blog->published_at) {
            $blog->published_at = now();
        } else {
            $blog->published_at = $request->published_at;
        }

        // Update blog
        $blog->title = $request->title;
        $blog->excerpt = $request->excerpt;
        $blog->content = $request->content;
        $blog->is_published = $request->has('is_published');

        $blog->save();

        return redirect()->route('seller.blogs.index')
            ->with('success', 'Blog berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $user = Auth::user();
        $store = $user->store;

        // Check if store can manage blogs and owns this blog
        if (!$store || !$store->canManageBlogs() || $blog->store_id !== $store->id) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus blog ini.');
        }

        // Delete featured image
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        // Delete blog
        $blog->delete();

        return redirect()->route('seller.blogs.index')
            ->with('success', 'Blog berhasil dihapus.');
    }
}
