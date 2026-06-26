<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Post::published()
            ->with(['categories', 'tags'])
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $request->string('category')));
        }

        if ($request->boolean('featured')) {
            $query->featured();
        }

        return ArticleResource::collection(
            $query->paginate(min($request->integer('per_page', 20), 50))
        );
    }

    public function show(Post $post): ArticleResource
    {
        abort_unless($post->status === 'published', 404);

        $post->load(['categories', 'tags']);

        return new ArticleResource($post);
    }
}
