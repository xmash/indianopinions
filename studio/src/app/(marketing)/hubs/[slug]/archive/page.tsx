import Link from 'next/link';
import { notFound, redirect } from 'next/navigation';
import { ArticleGridSection } from '@/components/sections/ArticleGridSection';
import { EmptyState } from '@/components/sections/EmptyState';
import { getHub, hubs } from '@/config/hubs';
import { getArticlesByCategory } from '@/lib/articles';
import { toArticleCardProps } from '@/lib/article-props';

type PageProps = {
  params: Promise<{ slug: string }>;
};

/** Desk slugs that support a per-section archive (excludes the site-wide Archive hub). */
export function isHubArchiveSlug(slug: string): boolean {
  return slug in hubs && slug !== 'archive';
}

export default async function HubArchivePage({ params }: PageProps) {
  const { slug } = await params;

  if (slug === 'archive') {
    redirect('/hubs/archive');
  }

  const hub = getHub(slug);

  if (!hub || !isHubArchiveSlug(slug)) {
    notFound();
  }

  const articles = await getArticlesByCategory(slug);

  return (
    <>
      <p className="hub-back-link">
        <Link href={`/hubs/${slug}`} className="link text-sm font-bold uppercase tracking-widest">
          ← Back to {hub.title}
        </Link>
      </p>

      {articles.length === 0 ? (
        <EmptyState
          title="No archived stories yet"
          message={`Published ${hub.title.toLowerCase()} coverage will appear here as the desk grows.`}
          action={{ label: `Back to ${hub.title}`, href: `/hubs/${slug}` }}
        />
      ) : (
        <ArticleGridSection
          title={`All stories · ${articles.length}`}
          articles={articles.map(toArticleCardProps)}
          columns={2}
        />
      )}
    </>
  );
}
