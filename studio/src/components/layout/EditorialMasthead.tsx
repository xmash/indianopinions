import Link from 'next/link';

import { HomeMasthead } from '@/components/layout/HomeMasthead';
import { SiteLogo } from '@/components/layout/SiteLogo';
import { site } from '@/config/site';

/** Homepage editorial masthead — meta strip, wordmark, tagline, date rule. */
export function EditorialMasthead() {
  const today = new Date().toLocaleDateString('en-GB', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });

  return (
    <header className="site-header site-header--brand">
      <div className="container-app site-header-inner">
        <div className="site-header-meta">
          <span>{site.mastheadLine}</span>
          <span className="site-header-meta-accent">{site.mastheadTagline}</span>
          <span>{site.editions}</span>
        </div>

        <div className="site-header-brand-row">
          <Link href="/" className="site-logo-link">
            <SiteLogo as="div" />
          </Link>
          <HomeMasthead />
        </div>

        <div className="site-header-rule">
          <span className="site-header-rule-side" aria-hidden="true" />
          <span className="site-header-date">{today}</span>
          <Link href="/brief" className="site-header-rule-side site-header-brief-link">
            Intelligence Brief
          </Link>
        </div>
      </div>
    </header>
  );
}
