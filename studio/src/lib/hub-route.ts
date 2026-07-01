import { getHub, type HubMeta } from '@/config/hubs';

export type HubRouteContext = {
  slug: string;
  hub: HubMeta;
  isArchive: boolean;
};

const BRIEF_PATH = /^\/brief(?:\/(\d{4}-\d{2}-\d{2}))?\/?$/;

/** Resolve hub metadata from a marketing pathname (`/hubs/politics`, `/brief/2026-06-01`, …). */
export function hubFromPathname(pathname: string): HubRouteContext | null {
  const briefMatch = pathname.match(BRIEF_PATH);

  if (briefMatch) {
    const hub = getHub('intelligence-brief');

    if (!hub) {
      return null;
    }

    return {
      slug: 'intelligence-brief',
      hub,
      isArchive: false,
    };
  }

  const match = pathname.match(/^\/hubs\/([^/]+)(?:\/archive)?\/?$/);

  if (!match) {
    if (pathname === '/media' || pathname.startsWith('/media/')) {
      const hub = getHub('media');

      if (!hub) {
        return null;
      }

      return {
        slug: 'media',
        hub,
        isArchive: false,
      };
    }

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
