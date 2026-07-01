import {getApiUrl} from '@/lib/api-url';

let cachedXsrfToken = '';

function readXsrfToken(): string {
  if (typeof document === 'undefined') {
    return cachedXsrfToken;
  }

  const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/);
  const token = match ? decodeURIComponent(match[1]) : '';

  if (token) {
    cachedXsrfToken = token;
  }

  return token || cachedXsrfToken;
}

export async function prepareStaffSignIn(): Promise<void> {
  const res = await fetch(`${getApiUrl()}/sanctum/csrf-cookie`, {
    credentials: 'include',
  });

  if (!res.ok) {
    throw new Error('session');
  }

  readXsrfToken();
}

export type StaffSignInPayload = {
  login: string;
  password: string;
  remember?: boolean;
};

export type StaffSignInResult = {
  redirect: string;
};

export class StaffSignInError extends Error {
  constructor(
    message: string,
    readonly code: 'session_expired' | 'invalid' | 'unknown' = 'unknown',
  ) {
    super(message);
    this.name = 'StaffSignInError';
  }
}

export async function submitStaffSignIn(
  payload: StaffSignInPayload,
): Promise<StaffSignInResult> {
  const xsrfToken = readXsrfToken();
  if (!xsrfToken) {
    throw new StaffSignInError('Session expired. Refreshing…', 'session_expired');
  }

  let res: Response;
  try {
    res = await fetch(`${getApiUrl()}/api/login`, {
      method: 'POST',
      credentials: 'include',
      redirect: 'error',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-XSRF-TOKEN': xsrfToken,
      },
      body: JSON.stringify({
        login: payload.login,
        password: payload.password,
        remember: payload.remember ?? false,
      }),
    });
  } catch {
    throw new StaffSignInError('Session expired. Refreshing…', 'session_expired');
  }

  const data = (await res.json().catch(() => ({}))) as {
    redirect?: string;
    message?: string;
    errors?: {login?: string[]};
  };

  if (res.status === 419) {
    throw new StaffSignInError('Session expired. Refreshing…', 'session_expired');
  }

  if (res.status === 422) {
    throw new StaffSignInError(
      data.errors?.login?.[0] ?? data.message ?? 'The provided credentials do not match our records.',
      'invalid',
    );
  }

  if (!res.ok || !data.redirect) {
    throw new StaffSignInError(data.message ?? 'Sign in failed. Please try again.', 'unknown');
  }

  return {redirect: data.redirect};
}
