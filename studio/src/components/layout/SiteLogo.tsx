import { site } from '@/config/site';

type SiteLogoProps = {
  as?: 'h1' | 'h2' | 'div' | 'span';
  className?: string;
};

/** Masthead wordmark — "Indian" and "Opinions" as separate words. */
export function SiteLogo({ as: Tag = 'h1', className = '' }: SiteLogoProps) {
  const [first, second] = site.nameParts;

  return (
    <Tag className={`site-logo${className ? ` ${className}` : ''}`} aria-label={site.name}>
      <span className="site-logo-word">{first}</span>
      <span className="site-logo-word">{second}</span>
    </Tag>
  );
}
