<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaVideo;
use App\Support\MediaStorage;
use Illuminate\Http\Request;

class MediaVideoController extends Controller
{
    public function index(Request $request)
    {
        $videos = MediaVideo::query()
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(24);

        $categories = MediaVideo::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('admin.media-videos.index', compact('videos', 'categories'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => 'required|file|mimetypes:video/mp4,video/webm,video/quicktime|max:512000',
        ]);

        $created = [];

        foreach ($request->file('files', []) as $file) {
            $video = MediaVideo::create([
                'video_url' => MediaStorage::storeUpload($file, 'videos'),
                'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'sort_order' => 0,
                'is_published' => true,
            ]);

            $created[] = [
                'id' => $video->id,
                'url' => $video->video_url,
                'title' => $video->title,
                'edit_url' => admin_route('admin.media-videos.edit', $video),
            ];
        }

        return response()->json(['videos' => $created]);
    }

    public function create()
    {
        $categories = MediaVideo::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('admin.media-videos.form', compact('categories'));
    }

    public function store(Request $request)
    {
        MediaVideo::create($this->validated($request));

        return admin_redirect('admin.media-videos.index')->with('success', 'Video added.');
    }

    public function edit(MediaVideo $media_video)
    {
        $categories = MediaVideo::whereNotNull('category')->distinct()->orderBy('category')->pluck('category');

        return view('admin.media-videos.form', ['video' => $media_video, 'categories' => $categories]);
    }

    public function update(Request $request, MediaVideo $media_video)
    {
        $media_video->update($this->validated($request, $media_video));

        return admin_redirect('admin.media-videos.index')->with('success', 'Video updated.');
    }

    public function destroy(MediaVideo $media_video)
    {
        MediaStorage::deleteByUrl($media_video->video_url);
        MediaStorage::deleteByUrl($media_video->thumbnail_url);
        $media_video->delete();

        return admin_redirect('admin.media-videos.index')->with('success', 'Video deleted.');
    }

    private function validated(Request $request, ?MediaVideo $video = null): array
    {
        return $request->validate([
            'video_url' => 'required|string|max:1000',
            'thumbnail_url' => 'nullable|string|max:1000',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'duration_seconds' => 'nullable|integer|min:0|max:86400',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'featured' => 'boolean',
            'is_published' => 'boolean',
        ]);
    }
}
