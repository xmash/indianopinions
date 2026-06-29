<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — Indian Opinions Admin</title>
    @vite(['resources/css/app.css'])
</head>
<body class="admin-shell" style="display:flex;align-items:center;justify-content:center;padding:24px;">
    <div style="width:100%;max-width:400px;">
        <div style="text-align:center;margin-bottom:32px;">
            <h1 style="font-size:1.5rem;font-weight:700;color:var(--navy);margin:0;">Indian Opinions</h1>
            <p style="color:var(--text-muted);margin-top:8px;font-size:14px;">Publishing admin</p>
        </div>

        <div class="card">
            <div class="card-body" style="display:grid;gap:16px;">
                <h2 style="font-size:1rem;font-weight:700;margin:0;">Staff sign in</h2>

                @if($errors->any())
                    <div class="alert alert-error" style="margin:0;">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" style="display:grid;gap:14px;">
                    @csrf
                    <div>
                        <label class="field-label">Email or username</label>
                        <input type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username" class="input" placeholder="editor or editor@indianopinions.com">
                    </div>
                    <div>
                        <label class="field-label">Password</label>
                        <input type="password" name="password" required class="input">
                    </div>
                    <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:var(--text-muted);">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
