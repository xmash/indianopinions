import { ArticleGridSection } from '@/components/sections/ArticleGridSection';
import { DailyBriefRail } from '@/components/sections/DailyBriefRail';
import { EmptyState } from '@/components/sections/EmptyState';
import { FeaturedArticleSection } from '@/components/sections/FeaturedArticleSection';
import { IntelligenceNetworkCta } from '@/components/sections/IntelligenceNetworkCta';
import { StrategyNotePanel } from '@/components/sections/StrategyNotePanel';
import { DataLabModule } from '@/components/editorial/DataLabModule';
import { WeeklyLetter } from '@/components/editorial/WeeklyLetter';
import { getHomepageLayout } from '@/lib/api';
import { toArticleCardProps } from '@/lib/article-props';

export default async function Home() {
  const layout = await getHomepageLayout();
  const hero = layout?.sections.hero?.items[0];
  const strategic = layout?.sections.strategic_analysis?.items ?? [];
  const brief = layout?.sections.daily_brief?.items ?? [];
  const latest = layout?.sections.latest?.items ?? [];

  const hasContent = Boolean(hero || strategic.length || brief.length || latest.length);

  if (!hasContent) {
    return (
      <EmptyState
        title="No stories published yet"
        message="The editorial desk is preparing the first edition. Check back soon or browse our intelligence brief."
        action={{ label: 'Intelligence Brief', href: '/brief' }}
      />
    );
  }

  return (
    <>
      {hero && <FeaturedArticleSection article={toArticleCardProps(hero)} />}

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 section">
        <div className="lg:col-span-8">
          <ArticleGridSection
            title="Strategic Analysis"
            articles={strategic.map(toArticleCardProps)}
          />

          <ArticleGridSection
            title="More Stories"
            articles={latest.map(toArticleCardProps)}
            className="mt-12"
          />

          <IntelligenceNetworkCta />
        </div>

        <aside className="sidebar-panel lg:col-span-4">
          <DailyBriefRail articles={brief.map(toArticleCardProps)} />
          <StrategyNotePanel />
        </aside>
      </div>

      <DataLabModule />
      <WeeklyLetter />
    </>
  );
}
