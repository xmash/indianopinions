import type { ApiArticle } from '@/lib/api';

const API_URL = process.env.NEXT_PUBLIC_API_URL || process.env.API_URL || 'http://localhost:8000';

export async function getArticlesByCategory(
  category: string,
  perPage = 50,
): Promise<ApiArticle[]> {
  try {
    const response = await fetch(
      `${API_URL}/api/articles?category=${encodeURIComponent(category)}&per_page=${perPage}`,
      { next: { revalidate: 60 } },
    );

    if (!response.ok) {
      return [];
    }

    const payload = (await response.json()) as { data?: ApiArticle[] };
    return payload.data ?? [];
  } catch {
    return [];
  }
}
