@php
$content = [
    ['route' => 'admin.dashboard',        'active' => 'admin.dashboard',       'label' => 'Dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['route' => 'admin.posts.index',      'active' => 'admin.posts.*',          'label' => 'Articles',    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
    ['route' => 'admin.gallery.index',    'active' => 'admin.gallery.*',        'label' => 'Gallery',     'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ['route' => 'admin.categories.index', 'active' => 'admin.categories.*',     'label' => 'Categories',  'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
    ['route' => 'admin.tags.index',       'active' => 'admin.tags.*',           'label' => 'Tags',        'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14'],
];
@endphp

<p class="px-3 mb-1 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Content</p>
@foreach($content as $link)
    <a href="{{ route($link['route']) }}"
       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
              {{ request()->routeIs($link['active']) ? 'bg-indigo-600 text-white' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
        </svg>
        {{ $link['label'] }}
    </a>
@endforeach
