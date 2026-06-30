@extends('layouts.admin')
@section('page_title', $post->title)

@section('content')
<x-admin.page-header :title="$post->title" :subtitle="'By '.$post->author.' · '.($post->categories->pluck('name')->join(', ') ?: 'Uncategorized').' · Updated '.$post->updated_at->format('M j, Y g:i A')">
    <x-slot:meta>
        <span class="badge {{ $post->statusEnum()->badgeClass() }}">{{ $post->statusEnum()->label() }}</span>
        @if($post->featured && auth()->user()->isEditor())
            <span class="badge badge-warning">Featured</span>
        @endif
    </x-slot:meta>
    <x-slot:actions>
        <a href="{{ admin_route('admin.posts.index') }}" class="btn btn-outline">Back to list</a>
        @can('update', $post)
            <a href="{{ admin_route('admin.posts.edit', $post) }}" class="btn btn-primary">Edit</a>
        @endcan
    </x-slot:actions>
</x-admin.page-header>

@if($post->editorial_notes && in_array($post->status, ['changes_requested', 'rejected']))
    <div class="alert alert-error">Editor notes: {{ $post->editorial_notes }}</div>
@endif

<div class="form-grid-2">
    <div style="display: grid; gap: 16px;">
        @if($post->featured_image)
            <div class="card">
                <div class="card-body">
                    <img src="{{ $post->featured_image }}" alt="" style="width:100%;max-height:360px;object-fit:cover;border-radius:var(--radius);">
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-head"><h3 class="card-title">Excerpt</h3></div>
            <div class="card-body">
                <p style="margin:0;line-height:1.6;">{{ $post->excerpt ?: '—' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h3 class="card-title">Body</h3></div>
            <div class="card-body prose" style="line-height:1.7;">
                @if($post->content)
                    {!! $post->content !!}
                @else
                    <p style="color:var(--text-muted);margin:0;">No body content yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div style="display: grid; gap: 16px; align-content: start;">
        <div class="card">
            <div class="card-head"><h3 class="card-title">Details</h3></div>
            <div class="card-body" style="display: grid; gap: 10px; font-size: 14px;">
                <div><span class="field-label">Slug</span><br>{{ $post->slug }}</div>
                <div><span class="field-label">Author account</span><br>{{ $post->authorUser?->name ?? '—' }}</div>
                <div><span class="field-label">Reading time</span><br>{{ $post->reading_time_label }}</div>
                @if($post->published_at)
                    <div><span class="field-label">Published</span><br>{{ $post->published_at->format('M j, Y g:i A') }}</div>
                @endif
                @if($post->tags->isNotEmpty())
                    <div>
                        <span class="field-label">Tags</span><br>
                        {{ $post->tags->pluck('name')->join(', ') }}
                    </div>
                @endif
            </div>
        </div>

        @if($workflowEvents->isNotEmpty())
            <div class="card">
                <div class="card-head"><h3 class="card-title">Workflow history</h3></div>
                <div class="card-body">
                    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: 12px;">
                        @foreach($workflowEvents as $event)
                            <li style="font-size: 13px; line-height: 1.5; border-left: 2px solid var(--border); padding-left: 12px;">
                                <div style="color: var(--text-muted); font-size: 12px;">
                                    {{ $event->created_at->format('M j, Y g:i A') }}
                                    · {{ $event->user?->name ?? 'System' }}
                                </div>
                                <div style="margin-top: 2px;">
                                    @if($event->from_status)
                                        <span class="badge badge-muted">{{ $event->fromStatusEnum()?->label() ?? $event->from_status }}</span>
                                        →
                                    @else
                                        Created →
                                    @endif
                                    <span class="badge {{ $event->toStatusEnum()->badgeClass() }}">{{ $event->toStatusEnum()->label() }}</span>
                                </div>
                                @if($event->note)
                                    <p style="margin: 6px 0 0; white-space: pre-wrap;">{{ $event->note }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @can('update', $post)
            <div class="card">
                <div class="card-body">
                    <a href="{{ admin_route('admin.posts.edit', $post) }}" class="btn btn-primary btn-block">Edit article</a>
                </div>
            </div>
        @endcan
    </div>
</div>
@endsection
