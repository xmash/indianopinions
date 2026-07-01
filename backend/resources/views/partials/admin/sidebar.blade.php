@php
$user = auth()->user();

$nav = [];

if ($user->can('view_dashboard')) {
    $nav[] = ['section' => 'Content', 'route' => 'admin.dashboard', 'label' => 'Dashboard', 'active' => 'admin.dashboard'];
}

if ($user->can('view_articles')) {
    $nav[] = ['section' => 'Content', 'route' => 'admin.posts.index', 'label' => 'Articles', 'active' => 'admin.posts.*'];
}

if ($user->can('view_review_queue')) {
    $nav[] = ['section' => 'Content', 'route' => 'admin.review.index', 'label' => 'Review Queue', 'active' => 'admin.review.*'];
}

if ($user->can('manage_layout')) {
    $nav[] = ['section' => 'Layout', 'route' => 'admin.layout.homepage', 'label' => 'Homepage', 'active' => 'admin.layout.homepage*'];

    foreach (config('editorial_layout.hub_slugs', []) as $hubSlug) {
        $nav[] = [
            'section' => 'Layout',
            'route' => 'admin.layout.hub',
            'route_params' => ['hubSlug' => $hubSlug],
            'label' => ucwords(str_replace('-', ' ', $hubSlug)),
            'active' => 'admin.layout.hub',
            'active_param' => $hubSlug,
            'nested' => true,
        ];
    }
}

if ($user->can('manage_categories')) {
    $nav[] = ['section' => 'Organise', 'route' => 'admin.categories.index', 'label' => 'Categories', 'active' => 'admin.categories.*'];
}

if ($user->can('manage_tags')) {
    $nav[] = ['section' => 'Organise', 'route' => 'admin.tags.index', 'label' => 'Tags', 'active' => 'admin.tags.*'];
}

if ($user->can('manage_gallery')) {
    $nav[] = ['section' => 'Organise', 'route' => 'admin.gallery.index', 'label' => 'Gallery', 'active' => 'admin.gallery.*'];
}

if ($user->can('manage_media')) {
    $nav[] = ['section' => 'Organise', 'route' => 'admin.media-videos.index', 'label' => 'Videos', 'active' => 'admin.media-videos.*'];
}

if ($user->can('manage_staff')) {
    $nav[] = ['section' => 'Admin', 'route' => 'admin.users.index', 'label' => 'Staff', 'active' => 'admin.users.*'];
}

if ($user->can('view_permissions_matrix')) {
    $nav[] = ['section' => 'Admin', 'route' => 'admin.permissions.index', 'label' => 'Permissions', 'active' => 'admin.permissions.*'];
}

$sections = collect($nav)->groupBy('section');
@endphp

<aside class="admin-sidebar">
    <div class="admin-sidebar-brand">
        <a href="{{ config('app.frontend_url') ?: 'http://localhost:9002' }}" target="_blank" rel="noopener" class="admin-sidebar-brand-link">
            <h1>Indian Opinions</h1>
        </a>
        <p>Publishing admin</p>
    </div>

    <nav class="admin-sidebar-nav" aria-label="Admin">
        @foreach($sections as $section => $links)
            <div class="admin-nav-group">
                <p class="admin-nav-section">{{ $section }}</p>
                <ul class="admin-nav-list">
                    @foreach($links as $link)
                        @php
                            $isActive = request()->routeIs($link['active']);
                            if ($isActive && isset($link['active_param'])) {
                                $isActive = request()->route('hubSlug') === $link['active_param'];
                            }
                            $href = isset($link['route_params'])
                                ? admin_route($link['route'], $link['route_params'])
                                : admin_route($link['route']);
                            $isNested = ! empty($link['nested']);
                            $indent = $isNested ? 56 : 32;
                        @endphp
                        <li @class(['is-nested' => $isNested])>
                            <a href="{{ $href }}"
                               class="admin-nav-link{{ $isActive ? ' active' : '' }}"
                               style="padding-left: {{ $indent }}px;">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </nav>
</aside>
