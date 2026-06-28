import { ArticleCard } from '@/components/editorial/ArticleCard';
import type { ArticleCardProps } from '@/lib/article-props';

type ArticleGridSectionProps = {
  title?: string;
  articles: ArticleCardProps[];
  columns?: 2 | 3;
  className?: string;
};

export function ArticleGridSection({
  title,
  articles,
  columns = 2,
  className = '',
}: ArticleGridSectionProps) {
  if (articles.length === 0) {
    return null;
  }

  const gridClass =
    columns === 3
      ? 'grid grid-cols-1 md:grid-cols-3 gap-12'
      : 'grid grid-cols-1 md:grid-cols-2 gap-x-12';

  return (
    <section className={`section${className ? ` ${className}` : ''}`}>
      {title && <h2 className="section-heading">{title}</h2>}
      <div className={gridClass}>
        {articles.map((article) => (
          <ArticleCard key={article.slug} {...article} />
        ))}
      </div>
    </section>
  );
}
