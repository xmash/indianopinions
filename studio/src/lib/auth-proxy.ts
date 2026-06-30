import {NextResponse} from 'next/server';

import {getApiUrl} from '@/lib/api-url';

/** Default post-login destination — Laravel dashboard at /admin */
export const ADMIN_HOME_PATH = '/admin';

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

export function isAuthSuccessRedirect(location: string): boolean {
  if (location === '') {
    return false;
  }

  let path: string;

  try {
    path = location.startsWith('http://') || location.startsWith('https://')
      ? new URL(location).pathname
      : location.split('?')[0];
  } catch {
    return false;
  }

  if (!path.startsWith('/')) {
    path = `/${path}`;
  }

  return (
    path.startsWith('/admin') &&
    path !== '/admin/login' &&
    !path.startsWith('/admin/login/')
  );
}

/** Map Laravel redirect target to a same-origin path on the public site. */
export function toPublicRedirectPath(location: string): string | null {
  if (!isAuthSuccessRedirect(location)) {
    return null;
  }

  try {
    if (location.startsWith('http://') || location.startsWith('https://')) {
      const parsed = new URL(location);
      return parsed.pathname + parsed.search;
    }
  } catch {
    return null;
  }

  return location.startsWith('/') ? location : `/${location}`;
}

/** After sign-in, always land on the dashboard unless Laravel sent another admin path. */
export function normalizeLoginRedirect(path: string | null): string {
  if (!path) {
    return ADMIN_HOME_PATH;
  }

  // Legacy backend defaulted to the articles list
  if (path === '/admin/posts') {
    return ADMIN_HOME_PATH;
  }

  return path;
}

/** @deprecated Use toPublicRedirectPath */
export function toPublicPath(location: string, requestUrl: string): string {
  return normalizeLoginRedirect(toPublicRedirectPath(location)) ?? ADMIN_HOME_PATH;
}

export function getApiOrigin(): string {
  return getApiUrl().replace(/\/$/, '');
}
