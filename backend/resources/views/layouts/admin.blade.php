<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Admin') — Indian Opinions</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell">
<div class="admin-layout">
    @include('partials.admin.sidebar')

    <div class="admin-main">
        <header class="admin-topbar">
            <div class="admin-topbar-inner">
                <span class="admin-topbar-name">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ admin_route('logout') }}" class="admin-topbar-logout">
                    @csrf
                    <button type="submit" class="admin-topbar-logout-btn" title="Sign out" aria-label="Sign out">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        <main class="admin-content">
            @include('partials.admin.alerts')
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
