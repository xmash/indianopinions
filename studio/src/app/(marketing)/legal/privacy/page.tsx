import type { Metadata } from 'next';
import { LegalDocument } from '@/components/legal/LegalDocument';
import { privacyPolicy } from '@/content/legal/privacy-policy';
import { site } from '@/config/site';

export const metadata: Metadata = {
  title: `Privacy Policy | ${site.name}`,
  description: privacyPolicy.description,
};

export default function PrivacyPolicyPage() {
  return <LegalDocument document={privacyPolicy} />;
}
