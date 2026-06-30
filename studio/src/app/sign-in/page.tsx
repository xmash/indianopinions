'use client';

import Link from 'next/link';
import {FormEvent, useEffect, useState} from 'react';

import {EditorialMasthead} from '@/components/layout/EditorialMasthead';

export default function SignInPage() {
  const [csrf, setCsrf] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [sessionReady, setSessionReady] = useState(false);

  useEffect(() => {
    let cancelled = false;

    fetch('/api/auth/csrf', {credentials: 'include'})
      .then(async (res) => {
        if (!res.ok) {
          throw new Error('session');
        }

        const data = (await res.json()) as {csrf?: string};
        if (!cancelled && data.csrf) {
          setCsrf(data.csrf);
          setSessionReady(true);
        }
      })
      .catch(() => {
        if (!cancelled) {
          setError('Could not start a sign-in session. Please refresh and try again.');
        }
      });

    return () => {
      cancelled = true;
    };
  }, []);

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError('');
    setLoading(true);

    try {
      const form = new FormData(event.currentTarget);
      const res = await fetch('/api/auth/login', {
        method: 'POST',
        body: form,
        credentials: 'include',
      });

      const data = (await res.json()) as {redirect?: string; error?: string};

      if (res.ok && data.redirect) {
        window.location.href = data.redirect;
        return;
      }

      setError(data.error ?? 'Sign in failed. Please try again.');
    } catch {
      setError('Sign in failed. Please try again.');
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="sign-in-page">
      <EditorialMasthead />

      <main className="sign-in-main">
        <div className="sign-in-card">
          <h2 className="sign-in-title">Sign in</h2>

          {error ? <p className="sign-in-error">{error}</p> : null}

          <form className="sign-in-form" onSubmit={onSubmit}>
            <input type="hidden" name="_token" value={csrf} />

            <div className="sign-in-field">
              <label htmlFor="login">Email or username</label>
              <input
                id="login"
                name="login"
                type="text"
                autoComplete="username"
                required
                disabled={!sessionReady || loading}
              />
            </div>

            <div className="sign-in-field">
              <label htmlFor="password">Password</label>
              <input
                id="password"
                name="password"
                type="password"
                autoComplete="current-password"
                required
                disabled={!sessionReady || loading}
              />
            </div>

            <label className="sign-in-remember">
              <input type="checkbox" name="remember" disabled={!sessionReady || loading} />
              Remember me
            </label>

            <button type="submit" className="sign-in-submit" disabled={!sessionReady || loading}>
              {loading ? 'Signing in…' : 'Sign in'}
            </button>
          </form>
        </div>

        <p className="sign-in-back">
          <Link href="/">← Back to Indian Opinions</Link>
        </p>
      </main>
    </div>
  );
}
