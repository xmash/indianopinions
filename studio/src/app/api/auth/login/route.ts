import {NextResponse} from 'next/server';

import {
  extractCsrfToken,
  forwardSetCookies,
  isAuthSuccessRedirect,
  normalizeLoginRedirect,
  toPublicRedirectPath,
} from '@/lib/auth-proxy';
import {getApiUrl} from '@/lib/api-url';

export const dynamic = 'force-dynamic';

const INVALID_CREDENTIALS = 'The provided credentials do not match our records.';

export async function POST(request: Request) {
  const form = await request.formData();
  const login = String(form.get('login') ?? '').trim();
  const password = String(form.get('password') ?? '');
  const token = String(form.get('_token') ?? '');
  const remember = form.get('remember') ? 'on' : '';

  if (!login || !password || !token) {
    return NextResponse.json({error: 'Email, password, and session are required.'}, {status: 400});
  }

  const body = new URLSearchParams({
    _token: token,
    login,
    password,
  });

  if (remember) {
    body.set('remember', remember);
  }

  const cookieHeader = request.headers.get('cookie') ?? '';
  const referer = request.headers.get('referer') ?? undefined;

  const response = await fetch(`${getApiUrl()}/admin/login`, {
    method: 'POST',
    headers: {
      Accept: 'text/html,application/xhtml+xml',
      'Content-Type': 'application/x-www-form-urlencoded',
      Cookie: cookieHeader,
      ...(referer ? {Referer: referer} : {}),
    },
    body,
    redirect: 'manual',
    cache: 'no-store',
  });

  const setCookies = response.headers.getSetCookie();

  if (response.status === 302 || response.status === 303) {
    const location = response.headers.get('location') ?? '';
    const redirectPath = toPublicRedirectPath(location);

    if (redirectPath && isAuthSuccessRedirect(location)) {
      const json = NextResponse.json({redirect: normalizeLoginRedirect(redirectPath)});
      forwardSetCookies(json, setCookies);

      return json;
    }

    const json = NextResponse.json({error: INVALID_CREDENTIALS}, {status: 401});
    forwardSetCookies(json, setCookies);

    return json;
  }

  const contentType = response.headers.get('content-type') ?? '';

  if (contentType.includes('text/html')) {
    const html = await response.text();

    if (html.includes('Page Expired') || html.includes('419')) {
      const json = NextResponse.json(
        {error: 'Session expired. Please refresh the page and try again.'},
        {status: 419},
      );
      forwardSetCookies(json, setCookies);

      return json;
    }

    if (extractCsrfToken(html)) {
      const json = NextResponse.json({error: INVALID_CREDENTIALS}, {status: 401});
      forwardSetCookies(json, setCookies);

      return json;
    }
  }

  const json = NextResponse.json({error: INVALID_CREDENTIALS}, {status: 401});
  forwardSetCookies(json, setCookies);

  return json;
}
