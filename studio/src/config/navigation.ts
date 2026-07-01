export type NavItem = {
  label: string;
  /** Shorter label for compact sticky header */
  shortLabel?: string;
  href: string;
};

export const primaryNav: NavItem[] = [
  { label: 'Politics', href: '/hubs/politics' },
  { label: 'Economy', href: '/hubs/economy' },
  { label: 'Foreign Affairs', shortLabel: 'Foreign', href: '/hubs/foreign-affairs' },
  { label: 'Society', href: '/hubs/society' },
  { label: 'Technology', shortLabel: 'Tech', href: '/hubs/technology' },
  { label: 'Diaspora', href: '/hubs/diaspora' },
  { label: 'Opinion', href: '/hubs/opinion' },
  { label: 'Analysis', href: '/hubs/analysis' },
  { label: 'Archive', href: '/hubs/archive' },
  { label: 'Media', href: '/media' },
];
