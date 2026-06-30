@extends('layouts.admin')
@section('page_title', isset($post) ? 'Edit Article' : 'New Article')

@section('content')
<x-admin.page-header :title="isset($post) ? 'Edit Article' : 'New Article'">
    @if(isset($post))
        <x-slot:meta>
            <span class="badge {{ $post->statusEnum()->badgeClass() }}">{{ $post->statusEnum()->label() }}</span>
        </x-slot:meta>
    @endif
    <x-slot:actions>
        @isset($post)
            <a href="{{ admin_route('admin.posts.show', $post) }}" class="btn btn-outline">View</a>
        @endisset
        <a href="{{ admin_route('admin.posts.index') }}" class="btn btn-outline">Back to list</a>
    </x-slot:actions>
</x-admin.page-header>

@if(isset($post))
    @php($lockedForWriter = auth()->user()->isWriter() && ! $post->isEditableByWriter())
@else
    @php($lockedForWriter = false)
@endif

@if($lockedForWriter)
    <div class="alert alert-info">This article is in review and cannot be edited until an editor sends it back for changes.</div>
@endif

@if(isset($post) && $post->editorial_notes && in_array($post->status, ['changes_requested', 'rejected']))
    <div class="alert alert-error">Editor notes: {{ $post->editorial_notes }}</div>
@endif

@error('workflow')
    <div class="alert alert-error">{{ $message }}</div>
@enderror

<div class="form-grid-2">
    <div class="card">
        <form id="article-form" method="POST" action="{{ isset($post) ? admin_route('admin.posts.update', $post) : admin_route('admin.posts.store') }}">
            @csrf
            @if(isset($post)) @method('PUT') @endif
            <fieldset @disabled($lockedForWriter) style="border: none; margin: 0; padding: 0;">
            <div class="card-body" style="display: grid; gap: 16px;">
                <div>
                    <label class="field-label">Headline</label>
                    <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required class="input">
                    @error('title')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="field-label">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $post->slug ?? '') }}" class="input">
                </div>
                <div>
                    <label class="field-label">Excerpt</label>
                    <textarea name="excerpt" rows="2" class="textarea">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                </div>
                <div>
                    <label class="field-label">Body (HTML / Markdown)</label>
                    <textarea name="content" rows="18" class="textarea" style="font-family: monospace;">{{ old('content', $post->content ?? '') }}</textarea>
                </div>

                <div class="card" style="box-shadow: none;">
                    <div class="card-head"><h3 class="card-title">Metadata</h3></div>
                    <div class="card-body" style="display: grid; gap: 12px;">
                        <div>
                            <label class="field-label">Byline</label>
                            <input type="text" name="author" value="{{ old('author', $post->author ?? auth()->user()->name) }}" class="input">
                        </div>
                        <div>
                            <label class="field-label">Featured image URL</label>
                            <input type="text" name="featured_image" value="{{ old('featured_image', $post->featured_image ?? '') }}" class="input">
                        </div>
                    </div>
                </div>

                <div class="card" style="box-shadow: none;">
                    <div class="card-head"><h3 class="card-title">Categories & Tags</h3></div>
                        <div class="card-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                @foreach($categories as $cat)
                                    <label style="display:flex;gap:8px;font-size:14px;margin-bottom:6px;">
                                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                                            {{ in_array($cat->id, old('categories', isset($post) ? $post->categories->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                        {{ $cat->name }}
                                    </label>
                                @endforeach
                            </div>
                            <div>
                                @foreach($tags as $tag)
                                    <label style="display:flex;gap:8px;font-size:14px;margin-bottom:6px;">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                            {{ in_array($tag->id, old('tags', isset($post) ? $post->tags->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                        {{ $tag->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                @unless($lockedForWriter)
                    <button type="submit" class="btn btn-primary">{{ isset($post) ? 'Save Article' : 'Save Draft' }}</button>
                @endunless
            </div>
            </fieldset>
        </form>
    </div>

    @if(isset($post))
        <div style="display: grid; gap: 16px; align-content: start;">
            @if(auth()->user()->isEditor())
                <div class="card">
                    <div class="card-head"><h3 class="card-title">Featured</h3></div>
                    <div class="card-body">
                        <label style="display:flex;gap:8px;align-items:flex-start;font-size:14px;cursor:pointer;">
                            <input type="checkbox" name="featured" value="1" form="article-form" style="margin-top:3px;"
                                @checked(old('featured', $post->featured ?? false))>
                            <span>
                                <strong>Featured article</strong><br>
                                <span style="color:var(--text-muted);font-size:13px;">Editor only. Prioritizes this story in homepage and hub layout fallbacks.</span>
                            </span>
                        </label>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-head"><h3 class="card-title">Workflow</h3></div>
                <div class="card-body" style="display: grid; gap: 16px;">
                    <div>
                        <span class="field-label">Current status</span>
                        <p style="margin: 6px 0 0;">
                            <span class="badge {{ $post->statusEnum()->badgeClass() }}">{{ $post->statusEnum()->label() }}</span>
                        </p>
                    </div>

                    @can('submit', $post)
                        <form method="POST" action="{{ admin_route('admin.posts.submit', $post) }}">
                            @csrf
                            <label class="field-label">Note to editors (optional)</label>
                            <textarea name="submission_notes" rows="2" class="textarea" style="min-height:60px;">{{ old('submission_notes', $post->submission_notes) }}</textarea>
                            <button type="submit" class="btn btn-navy btn-block" style="margin-top:8px;">Submit for Review</button>
                        </form>
                    @endcan

                    @if(auth()->user()->isEditor() && !empty($allowedTransitions))
                        <form method="POST" action="{{ admin_route('admin.posts.transition', $post) }}">
                            @csrf
                            <label class="field-label">Change status (editor only)</label>
                            <select name="status" class="input" required>
                                <option value="" disabled selected>Select next status…</option>
                                @foreach($allowedTransitions as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                            <label class="field-label" style="margin-top:12px;">Note</label>
                            <textarea name="note" rows="3" class="textarea" style="min-height:72px;" placeholder="Required when requesting changes or rejecting. Optional otherwise.">{{ old('note') }}</textarea>
                            <p style="font-size:12px;color:var(--text-muted);margin:6px 0 0;">Use <strong>Archived</strong> to unpublish a live article. All changes are logged below.</p>
                            <button type="submit" class="btn btn-primary btn-block" style="margin-top:8px;">Update Status</button>
                        </form>
                    @endif

                    @if(auth()->user()->isWriter() && in_array($post->status, ['submitted', 'in_review']))
                        <p style="font-size:14px;color:var(--text-muted);margin:0;">In the review queue — you will be notified when an editor sends it back for changes.</p>
                    @elseif($post->status === 'published' && auth()->user()->isWriter())
                        <p style="font-size:14px;color:var(--text-muted);margin:0;">Published {{ $post->published_at?->format('M j, Y g:i A') }}.</p>
                    @elseif($post->status === 'archived' && auth()->user()->isEditor())
                        <p style="font-size:14px;color:var(--text-muted);margin:0;">Archived — not visible on the public site. Restore to Draft to rework.</p>
                    @endif

                    @if(isset($workflowEvents) && $workflowEvents->isNotEmpty())
                        <div style="border-top: 1px solid var(--border); padding-top: 12px;">
                            <h4 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 12px;">Status history</h4>
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
                                            <p style="margin: 6px 0 0; color: var(--text); white-space: pre-wrap;">{{ $event->note }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        @if(auth()->user()->isEditor())
            <div class="card">
                <div class="card-head"><h3 class="card-title">Featured</h3></div>
                <div class="card-body">
                    <label style="display:flex;gap:8px;align-items:flex-start;font-size:14px;cursor:pointer;">
                        <input type="checkbox" name="featured" value="1" form="article-form" style="margin-top:3px;"
                            @checked(old('featured', false))>
                        <span>
                            <strong>Featured article</strong><br>
                            <span style="color:var(--text-muted);font-size:13px;">Editor only. Saves with the draft when you create the article.</span>
                        </span>
                    </label>
                </div>
            </div>
        @else
        <div class="card">
            <div class="card-body">
                <p style="font-size:14px;color:var(--text-muted);margin:0;">Save your draft first, then submit it for review.</p>
            </div>
        </div>
        @endif
    @endif
</div>
@endsection
