import type { Metadata } from 'next';
import { LegalDocument } from '@/components/legal/LegalDocument';
import { termsOfUse } from '@/content/legal/terms-of-use';
import { site } from '@/config/site';

export const metadata: Metadata = {
  title: `Terms of Use | ${site.name}`,
  description: termsOfUse.description,
};

export default function TermsOfUsePage() {
  return <LegalDocument document={termsOfUse} />;
}
