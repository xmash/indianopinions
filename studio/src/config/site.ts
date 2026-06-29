export const site = {
  name: 'Indian Opinions',
  nameParts: ['Indian', 'Opinions'] as const,
  tagline: 'Critical Perspectives for the Global Sub-continent',
  description:
    'Rigorous analysis and critical perspectives on Politics, Economy, Foreign Affairs, and Society for the modern subcontinent.',
  mastheadLine: 'Insight • Intelligence • Independent Editorial',
  mastheadTagline: 'RECLAIMING THE NARRATIVE',
  editions: 'New Delhi • London • New York',
  url: 'https://indianopinions.com',
  privacyEmail: 'privacy@indianopinions.com',
  footerLegal: '© 2026 Indian Opinions',
  adminLoginUrl:
    process.env.NEXT_PUBLIC_ADMIN_URL ??
    'https://indianopinions-indianopinions.up.railway.app/admin/login',
} as const;
