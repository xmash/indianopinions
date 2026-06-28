'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { HomeMasthead } from '@/components/layout/HomeMasthead';
import { HubMasthead } from '@/components/layout/HubMasthead';
import { PrimaryNav } from '@/components/layout/PrimaryNav';
import { SiteLogo } from '@/components/layout/SiteLogo';
import { StickySiteHeader, useStickyHeaderSentinel } from '@/components/layout/StickySiteHeader';
import { site } from '@/config/site';
import { hubFromPathname } from '@/lib/hub-route';

export function SiteHeader() {
  const pathname = usePathname();
  const isHome = pathname === '/';
  const hubContext = hubFromPathname(pathname);
  const showHomeTopNav = isHome || Boolean(hubContext);
  const stickyVisible = useStickyHeaderSentinel();
  const today = new Date().toLocaleDateString('en-GB', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });

  const brandRow = isHome || Boolean(hubContext);

  return (
    <>
      <header className={`site-header${brandRow ? ' site-header--brand' : ''}`}>
        <div className="container-app site-header-inner">
          {showHomeTopNav && (
            <div className="site-header-meta">
              <span>{site.mastheadLine}</span>
              <span className="site-header-meta-accent">{site.mastheadTagline}</span>
              <span>{site.editions}</span>
            </div>
          )}

          <div className={brandRow ? 'site-header-brand-row' : undefined}>
            <Link href="/" className="site-logo-link">
              <SiteLogo as={brandRow ? 'div' : 'h1'} />
            </Link>

            {isHome && <HomeMasthead />}
            {hubContext && <HubMasthead context={hubContext} />}
          </div>

          {showHomeTopNav && (
            <div className="site-header-rule">
              <span className="site-header-rule-side" aria-hidden="true" />
              <span className="site-header-date">{today}</span>
              <Link
                href="/brief"
                className={`site-header-rule-side site-header-brief-link${
                  pathname === '/brief' || pathname.startsWith('/brief/') ? ' site-header-brief-link-active' : ''
                }`}
              >
                Intelligence Brief
              </Link>
            </div>
          )}

          <div className="site-nav-bar">
            <PrimaryNav />
          </div>
        </div>
      </header>

      <div id="site-header-sentinel" className="site-header-sentinel" aria-hidden="true" />

      <StickySiteHeader visible={stickyVisible} />
    </>
  );
}
