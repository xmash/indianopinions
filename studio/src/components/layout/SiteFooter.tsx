import Link from 'next/link';
import { footerLinks } from '@/config/footer';
import { SiteLogo } from '@/components/layout/SiteLogo';
import { site } from '@/config/site';

export function SiteFooter() {
  return (
    <footer className="site-footer">
      <div className="container-app site-footer-inner">
        <div className="site-footer-brand">
          <SiteLogo as="h2" className="site-footer-logo" />
          <p className="site-footer-legal">{site.footerLegal}</p>
        </div>
        <nav className="site-footer-nav" aria-label="Footer">
          {footerLinks.map((link) => (
            <Link key={link.href} href={link.href} className="site-footer-link">
              {link.label}
            </Link>
          ))}
          <a
            href={site.adminLoginUrl}
            className="site-footer-link"
            target="_blank"
            rel="noopener noreferrer"
          >
            Staff Login
          </a>
        </nav>
      </div>
    </footer>
  );
}
