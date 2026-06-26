<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'posts_total' => Post::count(),
            'posts_published' => Post::published()->count(),
            'posts_draft' => Post::where('status', 'draft')->count(),
            'categories' => Category::count(),
            'tags' => Tag::count(),
        ];

        $recentPosts = Post::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPosts'));
    }
}
