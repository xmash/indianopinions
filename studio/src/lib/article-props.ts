import {
  ApiArticle,
  articleCategory,
  formatArticleDate,
} from '@/lib/api';

export type ArticleCardProps = {
  slug: string;
  category: string;
  title: string;
  excerpt: string;
  author: string;
  date: string;
  image?: string;
};

export function toArticleCardProps(article: ApiArticle): ArticleCardProps {
  return {
    slug: article.slug,
    category: articleCategory(article),
    title: article.title,
    excerpt: article.excerpt || '',
    author: article.author,
    date: formatArticleDate(article.published_at) || article.reading_time_label || '',
    image: article.featured_image || undefined,
  };
}
