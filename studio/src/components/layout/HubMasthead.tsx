import type { HubRouteContext } from '@/lib/hub-route';

type HubMastheadProps = {
  context: HubRouteContext;
};

export function HubMasthead({ context }: HubMastheadProps) {
  const { hub, isArchive } = context;
  const title = isArchive ? `${hub.title} Archive` : hub.title;
  const description = isArchive
    ? `Full published archive for ${hub.title.toLowerCase()} — every story on this desk.`
    : hub.description;

  return (
    <div className="site-hub-head">
      <h1 className="site-hub-title">{title}</h1>
      <p className="site-hub-description">{description}</p>
    </div>
  );
}
