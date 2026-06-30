import {NextResponse} from 'next/server';

import {extractCsrfToken, forwardSetCookies} from '@/lib/auth-proxy';
import {getApiUrl} from '@/lib/api-url';

export const dynamic = 'force-dynamic';

export async function GET() {
  const response = await fetch(`${getApiUrl()}/admin/login`, {
    cache: 'no-store',
    headers: {Accept: 'text/html'},
  });

  const html = await response.text();
  const csrf = extractCsrfToken(html);

  if (!csrf) {
    return NextResponse.json({error: 'Could not load sign-in session.'}, {status: 502});
  }

  const json = NextResponse.json({csrf});
  forwardSetCookies(json, response.headers.getSetCookie());

  return json;
}
