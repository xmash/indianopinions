import type { LegalDocumentData } from '@/content/legal/types';
import { PageHeader } from '@/components/sections/PageHeader';

type LegalDocumentProps = {
  document: LegalDocumentData;
};

export function LegalDocument({ document }: LegalDocumentProps) {
  return (
    <article className="legal-page max-w-3xl mx-auto">
      <PageHeader
        title={document.title}
        description={document.description}
        meta={`Last updated ${document.lastUpdated}`}
        size="default"
        className="border-b border-border pb-8 mb-10"
      />

      <div className="legal-content prose prose-lg max-w-none pb-16">
        {document.sections.map((section) => (
          <section key={section.title} className="legal-section">
            <h2>{section.title}</h2>
            {section.content.map((block, index) => (
              <div key={`${section.title}-${index}`}>{block}</div>
            ))}
          </section>
        ))}
      </div>
    </article>
  );
}
