import Link from 'next/link';
import type { LegalDocumentData } from '@/content/legal/types';
import { site } from '@/config/site';

export const privacyPolicy: LegalDocumentData = {
  title: 'Privacy Policy',
  description: 'How Indian Opinions collects, uses, and protects information when you visit our website.',
  lastUpdated: '26 June 2026',
  sections: [
    {
      title: 'Overview',
      content: [
        <>
          At {site.name}, accessible from {site.url.replace('https://', '')}, we respect your privacy. This
          Privacy Policy explains what information we collect when you visit our editorial website, how we use
          it, and the choices available to you.
        </>,
        <>
          This policy applies to our online activities only and covers information shared with or collected
          by us through this website. It does not apply to offline communications or third-party websites
          linked from our pages.
        </>,
        <>
          If you have questions about this policy, contact us at{' '}
          <a href={`mailto:${site.privacyEmail}`}>{site.privacyEmail}</a>.
        </>,
      ],
    },
    {
      title: 'Consent',
      content: [
        <>
          By using our website, you consent to this Privacy Policy. If you do not agree with its terms,
          please discontinue use of the site.
        </>,
      ],
    },
    {
      title: 'Information We Collect',
      content: [
        <>
          <strong>Information you provide.</strong> If you contact us by email or through a form, we may
          receive your name, email address, message contents, and any other details you choose to provide.
        </>,
        <>
          <strong>Automatically collected information.</strong> When you browse our site, we and our hosting
          providers may automatically collect technical data such as your IP address, browser type, device
          type, referring pages, pages viewed, and timestamps. This data is generally not used to identify
          you personally.
        </>,
        <>
          We do not require account registration to read our journalism. We do not knowingly collect payment
          card details or other financial information through this public website.
        </>,
      ],
    },
    {
      title: 'How We Use Your Information',
      content: [
        <>We use collected information to:</>,
        <ul key="use-list">
          <li>Provide, operate, and maintain the website and our editorial services</li>
          <li>Understand how readers use the site and improve content, layout, and performance</li>
          <li>Respond to enquiries and editorial correspondence</li>
          <li>Detect, prevent, and address technical issues or abuse</li>
          <li>Comply with applicable legal obligations</li>
        </ul>,
        <>We do not sell your personal information.</>,
      ],
    },
    {
      title: 'Log Files',
      content: [
        <>
          {site.name} follows standard practice of using server log files. Log data may include IP address,
          browser type, Internet Service Provider, date and time stamps, and referring or exit pages. This
          information helps us analyse trends, administer the site, and improve reader experience.
        </>,
      ],
    },
    {
      title: 'Cookies',
      content: [
        <>
          We use cookies and similar technologies as described in our{' '}
          <Link href="/legal/cookies">Cookie Policy</Link>. Cookies help us remember preferences, measure
          traffic, and improve site functionality.
        </>,
      ],
    },
    {
      title: 'Third-Party Services',
      content: [
        <>
          We may use trusted third-party services for hosting, analytics, content delivery, or security.
          These providers process data on our behalf according to their own privacy policies. We encourage
          you to review the policies of any third-party services you interact with through our site.
        </>,
        <>
          Our site may link to external websites. We are not responsible for the privacy practices of those
          sites.
        </>,
      ],
    },
    {
      title: 'Data Retention',
      content: [
        <>
          We retain personal information only for as long as necessary to fulfil the purposes described in
          this policy, unless a longer retention period is required by law.
        </>,
      ],
    },
    {
      title: 'Your Privacy Rights',
      content: [
        <>
          Depending on where you live, you may have rights to access, correct, delete, restrict, or object to
          certain processing of your personal data, and to receive a portable copy of data you have provided.
        </>,
        <>
          <strong>California residents (CCPA).</strong> You may request disclosure of categories and specific
          pieces of personal data collected, request deletion, and opt out of any sale of personal data. We
          do not sell personal information.
        </>,
        <>
          <strong>EEA and UK residents (GDPR).</strong> You have rights including access, rectification,
          erasure, restriction, objection, and data portability, subject to applicable law.
        </>,
        <>
          To exercise these rights, contact{' '}
          <a href={`mailto:${site.privacyEmail}`}>{site.privacyEmail}</a>. We will respond within one month
          where required by law.
        </>,
      ],
    },
    {
      title: 'Children’s Information',
      content: [
        <>
          Protecting children online is important to us. {site.name} does not knowingly collect personally
          identifiable information from children under 13. If you believe a child has provided such
          information, please contact us and we will promptly remove it from our records.
        </>,
      ],
    },
    {
      title: 'Changes to This Policy',
      content: [
        <>
          We may update this Privacy Policy from time to time. The &ldquo;Last updated&rdquo; date at the top
          of this page will reflect any revisions. Continued use of the site after changes constitutes
          acceptance of the updated policy.
        </>,
      ],
    },
  ],
};
