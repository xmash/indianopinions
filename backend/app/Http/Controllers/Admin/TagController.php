<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')->orderBy('name')->paginate(30);
        return view('admin.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        Tag::create(['name' => $request->name]);
        return admin_redirect('admin.tags.index')->with('success', 'Tag created.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return admin_redirect('admin.tags.index')->with('success', 'Tag deleted.');
    }
}
