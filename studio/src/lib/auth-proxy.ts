import {NextResponse} from 'next/server';

import {getApiUrl} from '@/lib/api-url';

export function stripCookieDomain(setCookie: string): string {
  return setCookie.replace(/;\s*Domain=[^;]*/gi, '');
}

export function forwardSetCookies(target: NextResponse, setCookies: string[]): void {
  for (const raw of setCookies) {
    target.headers.append('Set-Cookie', stripCookieDomain(raw));
  }
}

export function extractCsrfToken(html: string): string | null {
  const match = html.match(/name="_token" value="([^"]+)"/);

  return match?.[1] ?? null;
}

export function toPublicPath(location: string, requestUrl: string): string {
  const origin = new URL(requestUrl).origin;
  const apiUrl = getApiUrl();

  if (location.startsWith(apiUrl)) {
    return origin + location.slice(apiUrl.length);
  }

  try {
    const parsed = new URL(location);
    if (parsed.hostname.includes('railway.app')) {
      return origin + parsed.pathname + parsed.search;
    }
  } catch {
    // relative or invalid — fall through
  }

  if (location.startsWith('/')) {
    return origin + location;
  }

  return location;
}
