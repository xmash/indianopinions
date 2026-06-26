<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(
            Category::orderBy('name')->get()
        );
    }

    public function show(Category $category): CategoryResource
    {
        $category->load(['posts' => fn ($q) => $q->published()->with(['categories', 'tags'])->latest('published_at')]);

        return new CategoryResource($category);
    }
}
