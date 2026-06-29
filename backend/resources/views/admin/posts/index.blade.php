@extends('layouts.admin')
@section('page_title', 'Articles')

@section('content')
<x-admin.page-header title="Articles" subtitle="Draft, review, and publish stories">
    <x-slot:actions>
        @can('create', App\Models\Post::class)
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ New Article</a>
        @endcan
    </x-slot:actions>
</x-admin.page-header>

<div class="card" style="margin-bottom: 16px;">
    <div class="card-body page-toolbar">
        <a href="{{ route('admin.posts.index') }}" class="badge {{ !$currentStatus ? 'badge-primary' : 'badge-muted' }}">All</a>
        @foreach($statuses as $status)
            <a href="{{ route('admin.posts.index', ['status' => $status->value]) }}"
               class="badge {{ $currentStatus === $status->value ? 'badge-primary' : 'badge-muted' }}">
                {{ $status->label() }}
            </a>
        @endforeach
    </div>
</div>

<div class="card">
    @if($posts->isEmpty())
        <div class="empty">No articles match this filter.</div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Categories</th>
                    <th>Status</th>
                    <th>Updated</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                    <tr class="table-row-link" onclick="window.location='{{ route('admin.posts.show', $post) }}'" style="cursor: pointer;">
                        <td>
                            <a href="{{ route('admin.posts.show', $post) }}" class="link" onclick="event.stopPropagation()"><strong>{{ $post->title }}</strong></a>
                            @if($post->featured && auth()->user()->isEditor())<br><span class="badge badge-warning">Featured</span>@endif
                        </td>
                        <td>{{ $post->authorUser?->name ?? $post->author }}</td>
                        <td>{{ $post->categories->pluck('name')->join(', ') ?: '—' }}</td>
                        <td><span class="badge {{ $post->statusEnum()->badgeClass() }}">{{ $post->statusEnum()->label() }}</span></td>
                        <td>{{ $post->updated_at->format('M j, Y') }}</td>
                        <td style="white-space: nowrap;" onclick="event.stopPropagation()">
                            @can('update', $post)
                                <a href="{{ route('admin.posts.edit', $post) }}" class="link">Edit</a>
                            @endcan
                            @can('delete', $post)
                                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" style="display:inline" onsubmit="return confirm('Delete this article?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--danger);">Delete</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($posts->hasPages())
            <div class="card-body" style="border-top: 1px solid var(--border);">{{ $posts->links() }}</div>
        @endif
    @endif
</div>
@endsection
