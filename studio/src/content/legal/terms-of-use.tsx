import Link from 'next/link';
import type { LegalDocumentData } from '@/content/legal/types';
import { site } from '@/config/site';

export const termsOfUse: LegalDocumentData = {
  title: 'Terms of Use',
  description: 'Terms and conditions governing your use of the Indian Opinions website.',
  lastUpdated: '26 June 2026',
  sections: [
    {
      title: 'Agreement',
      content: [
        <>
          These Terms of Use (&ldquo;Terms&rdquo;) govern your access to and use of the {site.name} website
          at {site.url.replace('https://', '')} (the &ldquo;Site&rdquo;). The Site is a copyrighted work
          belonging to {site.name}. By accessing or using the Site, you agree to these Terms. If you do not
          agree, do not use the Site.
        </>,
        <>
          You must be at least 18 years of age to use the Site, or have the permission of a parent or
          guardian where required by applicable law.
        </>,
        <>
          Additional guidelines or rules posted on specific features of the Site are incorporated into these
          Terms by reference.
        </>,
      ],
    },
    {
      title: 'Editorial Content',
      content: [
        <>
          {site.name} publishes news, analysis, commentary, and related editorial material. Articles may
          reflect the views of named authors and do not necessarily represent the views of {site.name} as a
          whole.
        </>,
        <>
          Content is provided for general information purposes only. It does not constitute legal, financial,
          investment, or other professional advice. You should seek appropriate professional guidance
          before acting on any information published on the Site.
        </>,
      ],
    },
    {
      title: 'Access and Licence',
      content: [
        <>
          Subject to these Terms, we grant you a limited, non-exclusive, non-transferable, revocable licence
          to access and use the Site for your personal, non-commercial reading and reference.
        </>,
        <>
          You may not, without our prior written consent:
        </>,
        <ul key="restrictions">
          <li>
            Reproduce, republish, distribute, sell, or commercially exploit Site content except as permitted
            by law (such as fair dealing or fair use) or by explicit attribution requirements we publish
          </li>
          <li>Modify, scrape, or systematically download content using automated tools</li>
          <li>Remove copyright, trademark, or other proprietary notices</li>
          <li>Use the Site to build a competing product or service</li>
          <li>Attempt to gain unauthorised access to our systems or interfere with Site operation</li>
        </ul>,
        <>
          We may change, suspend, or discontinue any part of the Site at any time without notice. We are not
          liable for any modification, suspension, or discontinuation.
        </>,
      ],
    },
    {
      title: 'Intellectual Property',
      content: [
        <>
          Unless otherwise stated, all content on the Site — including text, headlines, graphics, logos,
          layout, and software — is owned by {site.name} or its licensors and is protected by copyright,
          trademark, and other intellectual property laws.
        </>,
        <>
          The {site.name} name and wordmark may not be used in a manner that suggests endorsement or
          affiliation without our written permission.
        </>,
      ],
    },
    {
      title: 'Acceptable Use',
      content: [
        <>You agree not to use the Site to:</>,
        <ul key="aup">
          <li>Violate any applicable law or regulation</li>
          <li>Infringe the rights of others, including intellectual property and privacy rights</li>
          <li>Upload or transmit malware, spam, or harmful code</li>
          <li>Harass, abuse, or harm others</li>
          <li>Collect information about other users without consent</li>
          <li>Impersonate any person or entity</li>
        </ul>,
        <>
          We may investigate violations and take appropriate action, including restricting access and
          reporting conduct to relevant authorities.
        </>,
      ],
    },
    {
      title: 'Third-Party Links',
      content: [
        <>
          The Site may contain links to third-party websites or services. We provide these links for
          convenience only and do not endorse or control third-party content. Your use of third-party sites is
          at your own risk and subject to their terms and policies.
        </>,
      ],
    },
    {
      title: 'Privacy and Cookies',
      content: [
        <>
          Our collection and use of personal information is described in our{' '}
          <Link href="/legal/privacy">Privacy Policy</Link>. Our use of cookies is described in our{' '}
          <Link href="/legal/cookies">Cookie Policy</Link>.
        </>,
      ],
    },
    {
      title: 'Disclaimers',
      content: [
        <>
          THE SITE AND ITS CONTENT ARE PROVIDED ON AN &ldquo;AS IS&rdquo; AND &ldquo;AS AVAILABLE&rdquo;
          BASIS. TO THE FULLEST EXTENT PERMITTED BY LAW, {site.name.toUpperCase()} DISCLAIMS ALL WARRANTIES,
          EXPRESS OR IMPLIED, INCLUDING WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND
          NON-INFRINGEMENT.
        </>,
        <>
          We do not warrant that the Site will be uninterrupted, error-free, secure, or free of harmful
          components, or that content will be complete or accurate at all times.
        </>,
      ],
    },
    {
      title: 'Limitation of Liability',
      content: [
        <>
          TO THE MAXIMUM EXTENT PERMITTED BY LAW, {site.name.toUpperCase()} AND ITS OFFICERS, EMPLOYEES,
          CONTRIBUTORS, AND SUPPLIERS SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL,
          CONSEQUENTIAL, OR PUNITIVE DAMAGES, OR ANY LOSS OF PROFITS, DATA, OR GOODWILL, ARISING FROM YOUR
          USE OF OR INABILITY TO USE THE SITE.
        </>,
        <>
          Where liability cannot be excluded, our total liability for any claim relating to the Site shall
          not exceed one hundred U.S. dollars (US $100).
        </>,
      ],
    },
    {
      title: 'Indemnity',
      content: [
        <>
          You agree to indemnify and hold harmless {site.name} and its affiliates from any claims, damages,
          losses, or expenses (including reasonable legal fees) arising from your use of the Site or
          violation of these Terms.
        </>,
      ],
    },
    {
      title: 'Copyright Complaints',
      content: [
        <>
          We respect intellectual property rights. If you believe material on the Site infringes your
          copyright, please send a written notice to{' '}
          <a href={`mailto:${site.privacyEmail}`}>{site.privacyEmail}</a> including:
        </>,
        <ul key="dmca">
          <li>Your physical or electronic signature</li>
          <li>Identification of the copyrighted work claimed to have been infringed</li>
          <li>Identification of the material on the Site and its location</li>
          <li>Your contact information</li>
          <li>
            A statement of good-faith belief that use of the material is not authorised by the copyright
            owner, its agent, or the law
          </li>
          <li>
            A statement, under penalty of perjury, that the information in the notice is accurate and that
            you are authorised to act on behalf of the copyright owner
          </li>
        </ul>,
      ],
    },
    {
      title: 'Governing Law',
      content: [
        <>
          These Terms are governed by the laws of India, without regard to conflict-of-law principles,
          except where mandatory consumer protection laws in your jurisdiction provide otherwise.
        </>,
        <>
          Any dispute arising from these Terms or your use of the Site shall be subject to the exclusive
          jurisdiction of the courts located in New Delhi, India, unless applicable law requires otherwise.
        </>,
      ],
    },
    {
      title: 'Changes',
      content: [
        <>
          We may revise these Terms from time to time. Material changes will be indicated by updating the
          &ldquo;Last updated&rdquo; date on this page. Continued use of the Site after changes constitutes
          acceptance of the revised Terms.
        </>,
      ],
    },
    {
      title: 'Contact',
      content: [
        <>
          For questions about these Terms, contact{' '}
          <a href={`mailto:${site.privacyEmail}`}>{site.privacyEmail}</a>.
        </>,
      ],
    },
  ],
};
