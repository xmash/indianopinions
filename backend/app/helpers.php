<?php

/**
 * Same-origin admin path for links/forms behind the Netlify → Railway proxy.
 *
 * @param  mixed  $parameters
 */
function admin_route(string $name, mixed $parameters = [], bool $absolute = false): string
{
    return route($name, $parameters, $absolute);
}

/**
 * @param  mixed  $parameters
 */
function admin_redirect(string $name, mixed $parameters = []): \Illuminate\Http\RedirectResponse
{
    return redirect()->to(admin_route($name, $parameters));
}

/** Default admin landing: dashboard at /admin */
function admin_home(): string
{
    return admin_route('admin.dashboard');
}

/** Full URL for post-login redirect (admin subdomain in production). */
function admin_url(): string
{
    $configured = config('app.admin_url');

    if (is_string($configured) && $configured !== '') {
        return rtrim($configured, '/');
    }

    return rtrim((string) config('app.url'), '/').admin_home();
}

/**
 * Redirect to the public-site staff sign-in (Next.js).
 */
function staff_sign_in_url(): string
{
    $frontend = config('app.frontend_url');

    if (is_string($frontend) && $frontend !== '') {
        return rtrim($frontend, '/').'/sign-in';
    }

    return '/sign-in';
}
