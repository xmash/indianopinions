import { ArticleCard } from '@/components/editorial/ArticleCard';
import type { ArticleCardProps } from '@/lib/article-props';

type DailyBriefRailProps = {
  articles: ArticleCardProps[];
};

export function DailyBriefRail({ articles }: DailyBriefRailProps) {
  if (articles.length === 0) {
    return null;
  }

  return (
    <>
      <h2 className="section-heading text-accent border-accent/20">The Daily Brief</h2>
      <p className="text-xs text-muted-foreground mb-4 leading-relaxed">
        Lead story from each editorial desk — Politics through Analysis.
      </p>
      <div className="space-y-2">
        {articles.map((article) => (
          <ArticleCard key={article.slug} {...article} layout="minimal" />
        ))}
      </div>
    </>
  );
}
