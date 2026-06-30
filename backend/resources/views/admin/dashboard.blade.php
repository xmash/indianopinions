@extends('layouts.admin')
@section('page_title', 'Dashboard')

@section('content')
<x-admin.page-header title="Dashboard" :subtitle="'Signed in as '.auth()->user()->roleLabel()">
    <x-slot:actions>
        <a href="{{ admin_route('admin.posts.create') }}" class="btn btn-primary">+ New Article</a>
    </x-slot:actions>
</x-admin.page-header>

<div class="grid-stats" style="margin-bottom: 24px;">
    @foreach($stats as $stat)
        <div class="stat">
            <div class="stat-accent"></div>
            <div class="stat-body">
                <p class="stat-label">{{ $stat['label'] }}</p>
                <p class="stat-value">{{ $stat['value'] }}</p>
                <p class="stat-sub">{{ $stat['sub'] }}</p>
            </div>
        </div>
    @endforeach
</div>

<div style="display: grid; gap: 24px;">
    @if($reviewQueue->isNotEmpty())
        <div class="card">
            <div class="card-head">
                <h2 class="card-title">Review Queue</h2>
                <a href="{{ admin_route('admin.review.index') }}" class="link">Open queue</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Writer</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviewQueue as $post)
                        <tr>
                            <td><a href="{{ admin_route('admin.posts.show', $post) }}" class="link">{{ $post->title }}</a></td>
                            <td>{{ $post->authorUser?->name ?? $post->author }}</td>
                            <td>{{ $post->updated_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Recent Articles</h2>
            <a href="{{ admin_route('admin.posts.index') }}" class="link">View all</a>
        </div>
        @if($recentPosts->isEmpty())
            <div class="empty">No articles yet. <a href="{{ admin_route('admin.posts.create') }}" class="link">Write the first story</a></div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentPosts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->authorUser?->name ?? $post->author }}</td>
                            <td><span class="badge {{ $post->statusEnum()->badgeClass() }}">{{ $post->statusEnum()->label() }}</span></td>
                            <td><a href="{{ admin_route('admin.posts.show', $post) }}" class="link">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
