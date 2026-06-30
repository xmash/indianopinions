<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — Indian Opinions</title>
    @vite(['resources/css/app.css'])
</head>
<body class="admin-shell admin-login-page">
    <header class="admin-login-masthead">
        <div class="admin-login-masthead-inner">
            <div class="admin-login-meta">
                <span>Insight • Intelligence • Independent Editorial</span>
                <span class="admin-login-meta-accent">RECLAIMING THE NARRATIVE</span>
                <span>New Delhi • London • New York</span>
            </div>

            <div class="admin-login-brand-row">
                <a href="{{ config('app.frontend_url', 'https://indianopinions.com') }}" class="admin-login-brand-link">
                    <h1 class="admin-login-logo" aria-label="Indian Opinions">
                        <span class="admin-login-logo-word">Indian</span>
                        <span class="admin-login-logo-word">Opinions</span>
                    </h1>
                </a>
                <div class="admin-login-hub-head">
                    <p class="admin-login-hub-title">Critical Perspectives for the Global Sub-continent</p>
                </div>
            </div>
        </div>
    </header>

    <main class="admin-login-main">
        <div class="admin-login-main-inner">
            <div class="admin-login-card-wrap">
                <div class="card admin-login-card">
                    <div class="card-body admin-login-card-body">
                        <h2 class="admin-login-title">Sign in</h2>

                        @if($errors->any())
                            <div class="alert alert-error admin-login-alert">{{ $errors->first() }}</div>
                        @endif

                        <form method="POST" action="/admin/login" class="admin-login-form">
                            @csrf
                            <div>
                                <label class="field-label" for="login">Email or username</label>
                                <input
                                    id="login"
                                    type="text"
                                    name="login"
                                    value="{{ old('login') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="input"
                                >
                            </div>
                            <div>
                                <label class="field-label" for="password">Password</label>
                                <input id="password" type="password" name="password" required class="input" autocomplete="current-password">
                            </div>
                            <label class="admin-login-remember">
                                <input type="checkbox" name="remember"> Remember me
                            </label>
                            <button type="submit" class="btn btn-primary btn-block admin-login-submit">Sign in</button>
                        </form>
                    </div>
                </div>
            </div>

            <p class="admin-login-back">
                <a href="{{ config('app.frontend_url', 'https://indianopinions.com') }}">← Back to Indian Opinions</a>
            </p>
        </div>
    </main>
</body>
</html>
