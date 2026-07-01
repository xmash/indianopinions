<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaVideoResource;
use App\Models\MediaVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MediaVideoController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $videos = MediaVideo::query()
            ->published()
            ->when($request->boolean('featured'), fn ($q) => $q->where('featured', true))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(min((int) $request->integer('per_page', 24), 48));

        return MediaVideoResource::collection($videos);
    }

    public function show(MediaVideo $mediaVideo): MediaVideoResource
    {
        abort_unless($mediaVideo->is_published, 404);

        return new MediaVideoResource($mediaVideo);
    }
}
