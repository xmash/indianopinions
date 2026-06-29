@extends('layouts.admin')
@section('page_title', 'Articles')

@section('content')
@php
    $listParams = array_filter([
        'search' => $currentSearch ?: null,
        'author' => $currentAuthor ?: null,
        'category' => $currentCategory ?: null,
        'sort' => $currentSort !== 'updated' ? $currentSort : null,
        'direction' => ($currentSort !== 'updated' && $currentDirection) || ($currentSort === 'updated' && $currentDirection !== 'desc')
            ? $currentDirection
            : null,
    ], fn ($value) => $value !== null && $value !== '');
@endphp

<x-admin.page-header title="Articles" subtitle="Draft, review, and publish stories">
    <x-slot:actions>
        @can('create', App\Models\Post::class)
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ New Article</a>
        @endcan
    </x-slot:actions>
</x-admin.page-header>

<div class="card" style="margin-bottom: 16px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="articles-search-form">
            @if($currentStatus)
                <input type="hidden" name="status" value="{{ $currentStatus }}">
            @endif
            @if($currentAuthor)
                <input type="hidden" name="author" value="{{ $currentAuthor }}">
            @endif
            @if($currentCategory)
                <input type="hidden" name="category" value="{{ $currentCategory }}">
            @endif
            @if($currentSort !== 'updated')
                <input type="hidden" name="sort" value="{{ $currentSort }}">
            @endif
            @if($currentDirection && ($currentSort !== 'updated' || $currentDirection !== 'desc'))
                <input type="hidden" name="direction" value="{{ $currentDirection }}">
            @endif
            <label class="field-label" for="article-search">Search articles</label>
            <div class="articles-search-row">
                <input
                    id="article-search"
                    type="search"
                    name="search"
                    value="{{ $currentSearch }}"
                    class="input"
                    placeholder="Title, slug, author, or excerpt…"
                >
                <button type="submit" class="btn btn-primary">Search</button>
                @if($currentSearch)
                    <a href="{{ route('admin.posts.index', array_merge($listParams, $currentStatus ? ['status' => $currentStatus] : [])) }}" class="btn btn-outline">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card" style="margin-bottom: 16px;">
    <div class="card-body page-toolbar">
        <a href="{{ route('admin.posts.index', $listParams) }}" class="badge {{ !$currentStatus ? 'badge-primary' : 'badge-muted' }}">All</a>
        @foreach($statuses as $status)
            @php
                $statusParams = $listParams;
                if ($currentStatus !== $status->value) {
                    $statusParams['status'] = $status->value;
                } else {
                    unset($statusParams['status']);
                }
            @endphp
            <a href="{{ route('admin.posts.index', $statusParams) }}"
               class="badge {{ $currentStatus === $status->value ? 'badge-primary' : 'badge-muted' }}">
                {{ $status->label() }}
            </a>
        @endforeach
    </div>
</div>

<div class="card" style="margin-bottom: 16px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="articles-filter-form">
            @if($currentSearch)
                <input type="hidden" name="search" value="{{ $currentSearch }}">
            @endif
            @if($currentStatus)
                <input type="hidden" name="status" value="{{ $currentStatus }}">
            @endif

            <div class="articles-filter-grid">
                @if($authors->isNotEmpty())
                    <div>
                        <label class="field-label" for="filter-author">Author</label>
                        <select id="filter-author" name="author" class="select">
                            <option value="">All authors</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" @selected($currentAuthor === $author->id)>{{ $author->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="field-label" for="filter-category">Category</label>
                    <select id="filter-category" name="category" class="select">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected($currentCategory === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label" for="filter-status">Status</label>
                    <select id="filter-status" name="status" class="select">
                        <option value="">All statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" @selected($currentStatus === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label" for="filter-sort">Sort by</label>
                    <select id="filter-sort" name="sort" class="select">
                        <option value="updated" @selected($currentSort === 'updated')>Last updated</option>
                        <option value="title" @selected($currentSort === 'title')>Title</option>
                        <option value="author" @selected($currentSort === 'author')>Author</option>
                        <option value="category" @selected($currentSort === 'category')>Category</option>
                        <option value="status" @selected($currentSort === 'status')>Status</option>
                    </select>
                </div>

                <div>
                    <label class="field-label" for="filter-direction">Order</label>
                    <select id="filter-direction" name="direction" class="select">
                        <option value="desc" @selected($currentDirection === 'desc')>Descending</option>
                        <option value="asc" @selected($currentDirection === 'asc')>Ascending</option>
                    </select>
                </div>
            </div>

            <div class="articles-filter-actions">
                <button type="submit" class="btn btn-primary">Apply filters</button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline">Reset all</a>
            </div>
        </form>
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
