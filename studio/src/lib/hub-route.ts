import { getHub, type HubMeta } from '@/config/hubs';

export type HubRouteContext = {
  slug: string;
  hub: HubMeta;
  isArchive: boolean;
};

/** Resolve hub metadata from a marketing pathname (`/hubs/politics`, `/hubs/politics/archive`). */
export function hubFromPathname(pathname: string): HubRouteContext | null {
  const match = pathname.match(/^\/hubs\/([^/]+)(?:\/archive)?\/?$/);

  if (!match) {
    return null;
  }

  const slug = match[1];
  const hub = getHub(slug);

  if (!hub) {
    return null;
  }

  return {
    slug,
    hub,
    isArchive: pathname.endsWith('/archive'),
  };
}
