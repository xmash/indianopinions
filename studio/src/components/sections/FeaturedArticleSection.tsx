import { ArticleCard } from '@/components/editorial/ArticleCard';
import type { ArticleCardProps } from '@/lib/article-props';

type FeaturedArticleSectionProps = {
  article: ArticleCardProps;
};

export function FeaturedArticleSection({ article }: FeaturedArticleSectionProps) {
  return (
    <section className="section">
      <ArticleCard {...article} layout="featured" />
    </section>
  );
}
