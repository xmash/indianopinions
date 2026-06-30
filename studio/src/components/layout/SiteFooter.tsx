import Link from 'next/link';
import { footerLegalColumns } from '@/config/legal';
import { SiteLogo } from '@/components/layout/SiteLogo';
import { site } from '@/config/site';

export function SiteFooter() {
  const [leftLinks, rightLinks] = footerLegalColumns;

  return (
    <footer className="site-footer">
      <div className="container-app site-footer-inner">
        <div className="site-footer-brand">
          <SiteLogo as="h2" className="site-footer-logo" />
          <p className="site-footer-legal">{site.footerLegal}</p>
        </div>
        <nav className="site-footer-nav" aria-label="Footer">
          <div className="site-footer-nav-col">
            {leftLinks.map((link) => (
              <Link key={link.href} href={link.href} className="site-footer-link">
                {link.label}
              </Link>
            ))}
          </div>
          <div className="site-footer-nav-col">
            {rightLinks.map((link) => (
              <Link key={link.href} href={link.href} className="site-footer-link">
                {link.label}
              </Link>
            ))}
            <Link href="/sign-in" className="site-footer-link">
              Login
            </Link>
          </div>
        </nav>
      </div>
    </footer>
  );
}
