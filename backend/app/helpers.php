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

/**
 * Redirect to the public-site staff sign-in (Next.js).
 */
function staff_sign_in_url(): string
{
    return '/sign-in';
}
