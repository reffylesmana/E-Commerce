<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the blogs.
     */
    public function index()
    {
        // Ambil semua blog dengan pagination
        $blogs = Blog::with('store')->latest()->paginate(10);
        $featuredBlog = Blog::with('store')->where('is_published', true)->whereNotNull('featured_image')->first();
        
        return view('blogs.index', compact('blogs', 'featuredBlog'));
    }

    /**
     * Display the specified blog.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // Temukan blog berdasarkan slug
        $blog = Blog::where('slug', $slug)->with('store')->firstOrFail();
        
        // Ambil artikel terkait
        $relatedBlogs = Blog::where('id', '!=', $blog->id)->where('is_published', true)->latest()->take(3)->get();
        
        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }
}