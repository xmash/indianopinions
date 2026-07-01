@extends('layouts.admin')
@section('page_title', 'Videos')

@section('content')
<x-admin.page-header title="Videos" subtitle="Upload and manage video for the public Media section">

<div class="mb-8"
     x-data="videoUploader()"
     x-init="init()"
     @dragover.prevent="dragging = true"
     @dragleave.prevent="dragging = false"
     @drop.prevent="onDrop($event)">

    <div :class="dragging ? 'border-indigo-500 bg-indigo-50' : 'border-zinc-300 bg-white hover:border-indigo-400 hover:bg-zinc-50'"
         class="border-2 border-dashed rounded-xl p-10 text-center transition-all duration-150 cursor-pointer"
         @click="$refs.fileInput.click()">

        <svg class="w-10 h-10 mx-auto mb-3 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        <p class="text-sm font-semibold text-zinc-600">Drop videos here, or <span class="text-indigo-600">browse</span></p>
        <p class="text-xs text-zinc-400 mt-1">MP4, WebM, MOV · up to 500 MB each</p>

        <input type="file" x-ref="fileInput" multiple accept="video/mp4,video/webm,video/quicktime"
               class="hidden" @change="onFileInput($event)">
    </div>

    <div x-show="queue.length > 0" class="mt-4 space-y-2">
        <template x-for="(item, i) in queue" :key="i">
            <div class="flex items-center gap-3 bg-white border border-zinc-200 rounded-xl px-4 py-2.5">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-zinc-700 truncate" x-text="item.name"></p>
                    <div class="mt-1 h-1.5 bg-zinc-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-200"
                             :class="item.error ? 'bg-red-400' : item.done ? 'bg-emerald-400' : 'bg-indigo-500'"
                             :style="`width: ${item.progress}%`"></div>
                    </div>
                </div>
                <span class="text-xs font-medium shrink-0"
                      :class="item.error ? 'text-red-500' : item.done ? 'text-emerald-600' : 'text-zinc-400'"
                      x-text="item.error ? 'Error' : item.done ? 'Done' : item.progress + '%'"></span>
            </div>
        </template>
    </div>
</div>

<div class="flex items-center justify-between mb-5">
    <div class="flex items-center gap-3">
        @if($categories->isNotEmpty())
            <form method="GET" action="{{ admin_route('admin.media-videos.index') }}">
                <select name="category" onchange="this.form.submit()"
                        class="text-sm border border-zinc-200 rounded-lg px-3 py-1.5 text-zinc-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </form>
        @endif
        <span class="text-xs text-zinc-400">{{ $videos->total() }} video{{ $videos->total() !== 1 ? 's' : '' }}</span>
    </div>
    <a href="{{ admin_route('admin.media-videos.create') }}"
       class="px-4 py-2 border border-zinc-300 hover:bg-zinc-50 text-zinc-700 text-sm font-medium rounded-lg transition">
        + Add by URL
    </a>
</div>

@if($videos->isNotEmpty())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($videos as $video)
            <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden">
                <div class="aspect-video bg-zinc-900">
                    <video src="{{ $video->video_url }}" class="w-full h-full object-contain" controls preload="metadata"></video>
                </div>
                <div class="p-4 space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-semibold text-zinc-800 truncate">{{ $video->title ?: 'Untitled' }}</p>
                        @if($video->featured)
                            <span class="px-1.5 py-0.5 text-xs bg-amber-400 text-amber-900 rounded-full font-bold shrink-0">★</span>
                        @endif
                    </div>
                    @if($video->category)
                        <p class="text-xs text-zinc-500">{{ $video->category }}</p>
                    @endif
                    @unless($video->is_published)
                        <p class="text-xs font-medium text-amber-700">Draft</p>
                    @endunless
                    <div class="flex gap-2 pt-1">
                        <a href="{{ admin_route('admin.media-videos.edit', $video) }}"
                           class="flex-1 py-1.5 text-center text-xs bg-zinc-100 hover:bg-zinc-200 text-zinc-700 rounded-lg transition">
                            Edit
                        </a>
                        <form method="POST" action="{{ admin_route('admin.media-videos.destroy', $video) }}"
                              onsubmit="return confirm('Delete this video?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 text-xs bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $videos->withQueryString()->links() }}</div>
@else
    <div class="bg-white rounded-xl border border-zinc-200 p-16 text-center text-zinc-400">
        <p class="text-sm">No videos yet — drag some in above.</p>
    </div>
@endif

@endsection

@push('scripts')
<script>
function videoUploader() {
    return {
        dragging: false,
        queue: [],

        init() {},

        onDrop(e) {
            this.dragging = false;
            this.handleFiles([...e.dataTransfer.files]);
        },

        onFileInput(e) {
            this.handleFiles([...e.target.files]);
            e.target.value = '';
        },

        handleFiles(files) {
            const videos = files.filter(f => f.type.startsWith('video/'));
            videos.forEach(file => this.uploadFile(file));
        },

        uploadFile(file) {
            const item = { name: file.name, progress: 0, done: false, error: false };
            this.queue.push(item);

            const fd = new FormData();
            fd.append('files[]', file);
            fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}');

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ admin_route('admin.media-videos.upload') }}');

            xhr.upload.onprogress = e => {
                if (e.lengthComputable) item.progress = Math.round(e.loaded / e.total * 95);
            };

            xhr.onload = () => {
                item.progress = 100;
                if (xhr.status === 200) {
                    item.done = true;
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    item.error = true;
                }
            };

            xhr.onerror = () => { item.error = true; item.progress = 100; };
            xhr.send(fd);
        },
    };
}
</script>
@endpush
