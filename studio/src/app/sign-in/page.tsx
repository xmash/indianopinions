'use client';

import Link from 'next/link';
import {FormEvent, useEffect, useState} from 'react';

import {EditorialMasthead} from '@/components/layout/EditorialMasthead';
import {
  prepareStaffSignIn,
  StaffSignInError,
  submitStaffSignIn,
} from '@/lib/auth-api';

export default function SignInPage() {
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [sessionReady, setSessionReady] = useState(false);

  useEffect(() => {
    let cancelled = false;

    prepareStaffSignIn()
      .then(() => {
        if (!cancelled) {
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

    const form = new FormData(event.currentTarget);
    const login = String(form.get('login') ?? '').trim();
    const password = String(form.get('password') ?? '');
    const remember = form.get('remember') === 'on';

    try {
      const {redirect} = await submitStaffSignIn({login, password, remember});
      window.location.assign(redirect);
    } catch (err) {
      if (err instanceof StaffSignInError && err.code === 'session_expired') {
        setError(err.message);
        window.location.reload();
        return;
      }

      if (err instanceof StaffSignInError) {
        setError(err.message);
        return;
      }

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
