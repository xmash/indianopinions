'use client';

import Link from 'next/link';
import { useEffect, useState } from 'react';
import { PrimaryNav } from '@/components/layout/PrimaryNav';
import { SiteLogo } from '@/components/layout/SiteLogo';

type StickySiteHeaderProps = {
  visible: boolean;
};

export function StickySiteHeader({ visible }: StickySiteHeaderProps) {
  return (
    <div
      className={`site-header-sticky${visible ? ' site-header-sticky-visible' : ''}`}
      aria-hidden={!visible}
    >
      <div className="container-app site-header-sticky-inner">
        <Link href="/" className="site-header-sticky-logo" tabIndex={visible ? 0 : -1}>
          <SiteLogo as="div" className="site-logo--sticky" />
        </Link>
        <PrimaryNav compact className="site-nav-sticky" linkClassName="site-nav-link-sticky" />
      </div>
    </div>
  );
}

/** Sentinel must sit directly below the main site header. */
export function useStickyHeaderSentinel() {
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const sentinel = document.getElementById('site-header-sentinel');

    if (!sentinel) {
      return;
    }

    const observer = new IntersectionObserver(
      ([entry]) => setVisible(!entry.isIntersecting),
      { root: null, threshold: 0 },
    );

    observer.observe(sentinel);

    return () => observer.disconnect();
  }, []);

  return visible;
}
