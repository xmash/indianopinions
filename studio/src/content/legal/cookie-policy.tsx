import Link from 'next/link';
import type { LegalDocumentData } from '@/content/legal/types';
import { site } from '@/config/site';

export const cookiePolicy: LegalDocumentData = {
  title: 'Cookie Policy',
  description: 'How Indian Opinions uses cookies and similar technologies on this website.',
  lastUpdated: '26 June 2026',
  sections: [
    {
      title: 'Overview',
      content: [
        <>
          This Cookie Policy explains how {site.name} ({site.url.replace('https://', '')}) uses cookies and
          similar technologies when you visit our website. It should be read alongside our{' '}
          <Link href="/legal/privacy">Privacy Policy</Link>.
        </>,
        <>
          Questions about cookies may be sent to{' '}
          <a href={`mailto:${site.privacyEmail}`}>{site.privacyEmail}</a>.
        </>,
      ],
    },
    {
      title: 'What Are Cookies',
      content: [
        <>
          Cookies are small text files stored on your device when you visit a website. They help the site
          remember your actions and preferences, and they allow us to understand how the site is used.
        </>,
      ],
    },
    {
      title: 'How We Use Cookies',
      content: [
        <>We use cookies for the following purposes:</>,
        <ul key="purpose-list">
          <li>
            <strong>Essential operation</strong> — to deliver pages securely and maintain basic site
            functionality
          </li>
          <li>
            <strong>Preferences</strong> — to remember choices that improve your reading experience
          </li>
          <li>
            <strong>Analytics</strong> — to understand traffic patterns, popular sections, and how readers
            navigate the site so we can improve our journalism and layout
          </li>
        </ul>,
        <>
          We do not use cookies to process payments or manage e-commerce transactions on this public
          editorial website.
        </>,
      ],
    },
    {
      title: 'Cookies We May Set',
      content: [
        <>
          <strong>Site functionality.</strong> Cookies that support core features such as load balancing,
          security, or remembering display preferences during your visit.
        </>,
        <>
          <strong>Analytics.</strong> We may use analytics services to measure readership and site
          performance. These services may set cookies to distinguish unique visitors and record page views.
          Analytics data is generally aggregated and not used to identify individual readers.
        </>,
      ],
    },
    {
      title: 'Third-Party Cookies',
      content: [
        <>
          Some cookies may be placed by third-party services we use, such as hosting, analytics, or embedded
          media providers. We do not control cookies set by third parties. Please refer to the relevant
          provider&apos;s policy for more information.
        </>,
        <>
          Links to external websites may also set cookies when you leave our site. Review those sites&apos;
          policies before interacting with them.
        </>,
      ],
    },
    {
      title: 'Managing Cookies',
      content: [
        <>
          You can control or delete cookies through your browser settings. Most browsers allow you to refuse
          cookies, delete existing cookies, or alert you when cookies are being set.
        </>,
        <>
          Disabling cookies may affect certain features of the site. Essential cookies required for security
          or basic delivery may still be used.
        </>,
        <>
          For general guidance on cookies, visit{' '}
          <a href="https://www.allaboutcookies.org" rel="noopener noreferrer" target="_blank">
            allaboutcookies.org
          </a>
          .
        </>,
      ],
    },
    {
      title: 'Changes to This Policy',
      content: [
        <>
          We may revise this Cookie Policy from time to time. Updates will be posted on this page with a
          revised &ldquo;Last updated&rdquo; date.
        </>,
      ],
    },
  ],
};
