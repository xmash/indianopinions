<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Str;

class IntelligenceBriefCompositionService
{
    public function __construct(private LayoutService $layout)
    {
    }

    /**
     * Hub rows for one brief edition — one blurb per desk (excludes site-wide archive hub).
     *
     * @return list<array{hub_slug: string, blurb: string, post_id: int|null}>
     */
    public function hubItemsForEdition(int $editionIndex, int $editionCount): array
    {
        $items = [];

        foreach (config('intelligence_brief.hub_slugs', []) as $position => $hubSlug) {
            $post = $this->leadPostForHub($hubSlug, $editionIndex, $editionCount);

            if (! $post) {
                continue;
            }

            $items[] = [
                'hub_slug' => $hubSlug,
                'blurb' => $this->blurbFromPost($post),
                'post_id' => $post->id,
                'position' => $position,
            ];
        }

        return $items;
    }

    /** @param  list<string>  $postSlugs */
    public function leadsFromPosts(array $postSlugs): array
    {
        $leads = [];

        foreach ($postSlugs as $postSlug) {
            $post = Post::published()->where('slug', $postSlug)->first();

            if (! $post) {
                continue;
            }

            $leads[] = [
                'headline' => $post->title,
                'blurb' => $this->blurbFromPost($post),
                'post_id' => $post->id,
            ];
        }

        return $leads;
    }

    public function leadPostForHub(string $hubSlug, int $editionIndex, int $editionCount): ?Post
    {
        $isLatestEdition = $editionIndex === max(0, $editionCount - 1);

        if ($isLatestEdition) {
            $layout = $this->layout->resolveHub($hubSlug);
            $heroId = $layout['sections']['hero']['items'][0]['id'] ?? null;

            if ($heroId) {
                $hero = Post::published()->find($heroId);

                if ($hero) {
                    return $hero;
                }
            }
        }

        $posts = Post::published()
            ->whereHas('categories', fn ($query) => $query->where('slug', $hubSlug))
            ->orderByDesc('published_at')
            ->get();

        if ($posts->isEmpty()) {
            return null;
        }

        return $posts->get($editionIndex % $posts->count());
    }

    public function blurbFromPost(Post $post): string
    {
        $parts = [];

        if ($post->excerpt) {
            $parts[] = trim(strip_tags($post->excerpt));
        }

        if ($post->content) {
            $parts[] = trim(preg_replace('/\s+/u', ' ', strip_tags($post->content)) ?? '');
        }

        $text = trim(implode(' ', array_filter($parts)));

        return $this->clampWords($text, 175);
    }

    private function clampWords(string $text, int $maxWords): string
    {
        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

        if ($words === false || count($words) <= $maxWords) {
            return $text;
        }

        return Str::finish(implode(' ', array_slice($words, 0, $maxWords)), '…');
    }
}
