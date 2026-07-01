@extends('layouts.admin')
@section('page_title', isset($video) ? 'Edit Video' : 'Add Video')

@section('content')
<x-admin.page-header :title="isset($video) ? 'Edit Video' : 'Add Video'">
    <x-slot:actions>
        <a href="{{ admin_route('admin.media-videos.index') }}" class="btn btn-outline">Back</a>
    </x-slot:actions>
</x-admin.page-header>

<form method="POST" action="{{ isset($video) ? admin_route('admin.media-videos.update', $video) : admin_route('admin.media-videos.store') }}" class="max-w-2xl">
    @csrf
    @if(isset($video)) @method('PUT') @endif

    <div class="space-y-5">
        <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-4"
             x-data="{ url: '{{ old('video_url', $video->video_url ?? '') }}' }">
            <div>
                <label class="admin-label">Video URL <span class="text-red-500">*</span></label>
                <input type="url" name="video_url" x-model="url"
                       value="{{ old('video_url', $video->video_url ?? '') }}"
                       required class="admin-input font-mono text-sm" placeholder="https://…">
                @error('video_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div x-show="url" class="rounded-xl overflow-hidden border border-zinc-200 bg-zinc-900 aspect-video">
                <video :src="url" class="w-full h-full object-contain" controls preload="metadata"></video>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-zinc-200 p-6 space-y-4">
            <div>
                <label class="admin-label">Thumbnail URL</label>
                <input type="url" name="thumbnail_url" value="{{ old('thumbnail_url', $video->thumbnail_url ?? '') }}"
                       class="admin-input font-mono text-sm" placeholder="https://… (optional)">
            </div>
            <div>
                <label class="admin-label">Title</label>
                <input type="text" name="title" value="{{ old('title', $video->title ?? '') }}" class="admin-input">
            </div>
            <div>
                <label class="admin-label">Description</label>
                <textarea name="description" rows="3" class="admin-input resize-none">{{ old('description', $video->description ?? '') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="admin-label">Category</label>
                    <input type="text" name="category" value="{{ old('category', $video->category ?? '') }}" class="admin-input" list="existing-cats">
                    <datalist id="existing-cats">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="admin-label">Duration (seconds)</label>
                    <input type="number" name="duration_seconds" value="{{ old('duration_seconds', $video->duration_seconds ?? '') }}" class="admin-input" min="0">
                </div>
            </div>
            <div>
                <label class="admin-label">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $video->sort_order ?? 0) }}" class="admin-input" min="0">
            </div>
            <div class="flex flex-col gap-2 pt-1">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="featured" value="0">
                    <input type="checkbox" name="featured" value="1" {{ old('featured', $video->featured ?? false) ? 'checked' : '' }}
                           class="rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-zinc-700">Featured on the Media page</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $video->is_published ?? true) ? 'checked' : '' }}
                           class="rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-zinc-700">Published on the public site</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm transition">
                {{ isset($video) ? 'Update Video' : 'Add Video' }}
            </button>
            <a href="{{ admin_route('admin.media-videos.index') }}" class="px-4 py-2 border border-zinc-300 text-zinc-600 hover:bg-zinc-50 rounded-lg text-sm transition">Cancel</a>
        </div>
    </div>
</form>
@endsection
