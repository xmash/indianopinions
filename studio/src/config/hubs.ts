export type HubMeta = {
  title: string;
  description: string;
};

export const hubs: Record<string, HubMeta> = {
  politics: {
    title: 'Politics & Governance',
    description:
      'Navigating the legislative currents and institutional frameworks of the subcontinent.',
  },
  economy: {
    title: 'Economy & Industry',
    description:
      'A macro and micro analysis of the financial architectures driving India’s trajectory.',
  },
  'foreign-affairs': {
    title: 'Foreign Affairs & Strategy',
    description:
      'Strategic assessments of India’s evolving footprint in a multipolar world order.',
  },
  society: {
    title: 'Society & Culture',
    description:
      'Exploring the sociological shifts and cultural revivals across regional boundaries.',
  },
  technology: {
    title: 'Technology & Innovation',
    description:
      'From deep-tech to digital public infrastructure: the engines of modern India.',
  },
  diaspora: {
    title: 'The Global Indian',
    description:
      'Voices and influence of the 32-million strong Indian community worldwide.',
  },
  opinion: {
    title: 'Commentary & Opinion',
    description:
      'Divergent viewpoints from leading analysts, diplomats, and industry stalwarts.',
  },
  analysis: {
    title: 'Strategic Analysis',
    description:
      'In-depth investigative and analytical reporting across policy and society.',
  },
  archive: {
    title: 'Historical Archive',
    description:
      'Accessing the historical records and turning points that shaped the modern state.',
  },
  media: {
    title: 'Media',
    description:
      'Video reports, interviews, and visual journalism from Indian Opinions.',
  },
  'intelligence-brief': {
    title: 'Intelligence Brief',
    description:
      'A daily digest of reporting across every editorial desk — two lead assessments and one dispatch per section, in five minutes.',
  },
};

export function getHub(slug: string): HubMeta | undefined {
  return hubs[slug];
}
