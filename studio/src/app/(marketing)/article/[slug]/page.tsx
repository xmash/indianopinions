import Image from 'next/image';
import { notFound } from 'next/navigation';
import { PageHeader } from '@/components/sections/PageHeader';
import { articleCategory, formatArticleDate, getArticle } from '@/lib/api';

export default async function ArticlePage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const article = await getArticle(slug);

  if (!article) {
    notFound();
  }

  const meta = (
    <>
      By {article.author}
      {article.published_at && (
        <>
          {' '}
          · {formatArticleDate(article.published_at)}
        </>
      )}
    </>
  );

  return (
    <article className="max-w-4xl mx-auto">
      <PageHeader
        eyebrow={articleCategory(article)}
        title={article.title}
        meta={meta}
        size="article"
        className="border-b-0 pb-0 mb-8"
      />

      {article.featured_image && (
        <div className="aspect-[16/9] relative mb-10">
          <Image src={article.featured_image} alt={article.title} fill className="object-cover" />
        </div>
      )}

      {article.excerpt && (
        <p className="text-xl text-muted-foreground leading-relaxed mb-10 font-headline italic">
          {article.excerpt}
        </p>
      )}

      {article.content && (
        <div
          className="prose prose-lg max-w-none pb-12"
          dangerouslySetInnerHTML={{ __html: article.content }}
        />
      )}
    </article>
  );
}
