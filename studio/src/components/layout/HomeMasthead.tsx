import { site } from '@/config/site';

export function HomeMasthead() {
  return (
    <div className="site-hub-head">
      <h1 className="site-hub-title">{site.tagline}</h1>
    </div>
  );
}
