import type { ReactNode } from 'react';

export type LegalSection = {
  title: string;
  content: ReactNode[];
};

export type LegalDocumentData = {
  title: string;
  description: string;
  lastUpdated: string;
  sections: LegalSection[];
};
