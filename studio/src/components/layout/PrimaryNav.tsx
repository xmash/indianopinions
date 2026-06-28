'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { primaryNav } from '@/config/navigation';

type PrimaryNavProps = {
  className?: string;
  linkClassName?: string;
  /** Compact labels for sticky header */
  compact?: boolean;
};

export function PrimaryNav({ className = '', linkClassName = '', compact = false }: PrimaryNavProps) {
  const pathname = usePathname();

  return (
    <nav className={`site-nav${className ? ` ${className}` : ''}`} aria-label="Primary">
      {primaryNav.map((item) => {
        const active = pathname === item.href || pathname.startsWith(`${item.href}/`);
        const label = compact && item.shortLabel ? item.shortLabel : item.label;

        return (
          <Link
            key={item.href}
            href={item.href}
            className={`site-nav-link${linkClassName ? ` ${linkClassName}` : ''}${
              active ? ' site-nav-link-active' : ''
            }`}
            aria-current={active ? 'page' : undefined}
            title={compact && item.shortLabel ? item.label : undefined}
          >
            {label}
          </Link>
        );
      })}
    </nav>
  );
}
