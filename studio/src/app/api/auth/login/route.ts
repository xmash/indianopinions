import {NextResponse} from 'next/server';

import {forwardSetCookies, toPublicPath} from '@/lib/auth-proxy';
import {getApiUrl} from '@/lib/api-url';

export const dynamic = 'force-dynamic';

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

  const response = await fetch(`${getApiUrl()}/admin/login`, {
    method: 'POST',
    headers: {
      Accept: 'text/html,application/xhtml+xml',
      'Content-Type': 'application/x-www-form-urlencoded',
      Cookie: cookieHeader,
    },
    body,
    redirect: 'manual',
    cache: 'no-store',
  });

  const setCookies = response.headers.getSetCookie();

  if (response.status === 302 || response.status === 303) {
    const location = response.headers.get('location') ?? '/admin/posts';
    const json = NextResponse.json({redirect: toPublicPath(location, request.url)});
    forwardSetCookies(json, setCookies);

    return json;
  }

  const json = NextResponse.json(
    {error: 'The provided credentials do not match our records.'},
    {status: 401},
  );
  forwardSetCookies(json, setCookies);

  return json;
}
