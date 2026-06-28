import type { Metadata } from 'next';
import { LegalDocument } from '@/components/legal/LegalDocument';
import { cookiePolicy } from '@/content/legal/cookie-policy';
import { site } from '@/config/site';

export const metadata: Metadata = {
  title: `Cookie Policy | ${site.name}`,
  description: cookiePolicy.description,
};

export default function CookiePolicyPage() {
  return <LegalDocument document={cookiePolicy} />;
}
