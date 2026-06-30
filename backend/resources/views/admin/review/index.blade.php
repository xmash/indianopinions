@extends('layouts.admin')
@section('page_title', 'Review Queue')

@section('content')
<x-admin.page-header title="Review Queue" subtitle="Submitted and in-review articles awaiting editor action" />

<div class="card">
    @if($posts->isEmpty())
        <div class="empty">The review queue is empty.</div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Writer</th>
                    <th>Status</th>
                    <th>Categories</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>
                            <a href="{{ admin_route('admin.posts.show', $post) }}" class="link">{{ $post->title }}</a>
                            @if($post->submission_notes)
                                <p style="font-size: 12px; color: var(--text-muted); margin: 4px 0 0;">{{ Str::limit($post->submission_notes, 80) }}</p>
                            @endif
                        </td>
                        <td>{{ $post->authorUser?->name ?? $post->author }}</td>
                        <td><span class="badge {{ $post->statusEnum()->badgeClass() }}">{{ $post->statusEnum()->label() }}</span></td>
                        <td>{{ $post->categories->pluck('name')->join(', ') ?: '—' }}</td>
                        <td>{{ $post->updated_at->diffForHumans() }}</td>
                        <td style="white-space: nowrap;">
                            @if($post->status === 'submitted')
                                <form method="POST" action="{{ admin_route('admin.review.start', $post) }}" style="display:inline">
                                    @csrf
                                    <button class="btn btn-outline btn-sm">Start Review</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ admin_route('admin.review.publish', $post) }}" style="display:inline">
                                @csrf
                                <button class="btn btn-primary btn-sm">Publish</button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="background: var(--surface);">
                            <div style="display: grid; gap: 12px; padding: 8px 0;">
                                <form method="POST" action="{{ admin_route('admin.review.changes', $post) }}" style="display:flex; gap:8px; align-items:flex-end;">
                                    @csrf
                                    <div style="flex:1">
                                        <label class="field-label">Request changes (required note)</label>
                                        <input type="text" name="editorial_notes" class="input" placeholder="Notes for the writer..." required>
                                    </div>
                                    <button type="submit" class="btn btn-outline btn-sm">Request Changes</button>
                                </form>
                                <form method="POST" action="{{ admin_route('admin.review.reject', $post) }}" style="display:flex; gap:8px; align-items:flex-end;">
                                    @csrf
                                    <div style="flex:1">
                                        <label class="field-label">Reject (required reason)</label>
                                        <input type="text" name="editorial_notes" class="input" placeholder="Reason for rejection..." required>
                                    </div>
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color: var(--danger);">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if($posts->hasPages())
            <div class="card-body">{{ $posts->links() }}</div>
        @endif
    @endif
</div>
@endsection
