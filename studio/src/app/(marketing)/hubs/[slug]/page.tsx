import Link from 'next/link';
import { notFound } from 'next/navigation';
import { WeeklyLetter } from '@/components/editorial/WeeklyLetter';
import { ArticleGridSection } from '@/components/sections/ArticleGridSection';
import { EmptyState } from '@/components/sections/EmptyState';
import { FeaturedArticleSection } from '@/components/sections/FeaturedArticleSection';
import { getHub } from '@/config/hubs';
import { getHubLayout } from '@/lib/api';
import { toArticleCardProps } from '@/lib/article-props';

export default async function HubPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const hub = getHub(slug);

  if (!hub) {
    notFound();
  }

  const layout = await getHubLayout(slug);
  const hero = layout?.sections.hero?.items[0];
  const grid = layout?.sections.grid?.items ?? [];
  const latest = layout?.sections.latest?.items ?? [];
  const hasContent = Boolean(hero || grid.length || latest.length);

  return (
    <>
      {!hasContent ? (
        <EmptyState
          title="No stories in this section yet"
          message={`We have not published ${hub.title.toLowerCase()} coverage yet. Explore other desks or return to the homepage.`}
          action={{ label: 'Back to homepage', href: '/' }}
        />
      ) : (
        <>
          {hero && <FeaturedArticleSection article={toArticleCardProps(hero)} />}

          <ArticleGridSection articles={grid.map(toArticleCardProps)} columns={3} />

          <ArticleGridSection
            title={`More in ${hub.title}`}
            articles={latest.map(toArticleCardProps)}
          />

          {slug !== 'archive' && (
            <p className="section pt-0">
              <Link href={`/hubs/${slug}/archive`} className="link font-bold uppercase tracking-widest text-sm">
                View full section archive →
              </Link>
            </p>
          )}
        </>
      )}

      <WeeklyLetter />
    </>
  );
}
