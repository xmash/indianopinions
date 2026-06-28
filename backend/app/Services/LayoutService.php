<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Http\Resources\ArticleResource;
use App\Models\LayoutSlot;
use App\Models\Post;
use Illuminate\Support\Collection;

class LayoutService
{
    /** @return array<string, mixed> */
    public function resolveHomepage(): array
    {
        return $this->resolvePage('homepage');
    }

    /** @return array<string, mixed> */
    public function resolveHub(string $hubSlug): array
    {
        return $this->resolvePage('hub', $hubSlug);
    }

    /** @return array<string, mixed> */
    private function resolvePage(string $page, ?string $hubSlug = null): array
    {
        $definitions = config("editorial_layout.{$page}", []);
        $usedPostIds = [];
        $sections = [];

        foreach ($definitions as $sectionKey => $definition) {
            $count = (int) ($definition['count'] ?? 1);
            $slots = [];
            $assigned = LayoutSlot::query()
                ->where('page', $page)
                ->where('section', $sectionKey)
                ->when($hubSlug, fn ($q) => $q->where('hub_slug', $hubSlug))
                ->when(! $hubSlug, fn ($q) => $q->whereNull('hub_slug'))
                ->orderBy('position')
                ->with(['post.categories', 'post.tags'])
                ->get()
                ->keyBy('position');

            for ($position = 0; $position < $count; $position++) {
                $post = $assigned->get($position)?->post;

                if ($post && $post->status === PostStatus::Published->value && ! in_array($post->id, $usedPostIds, true)) {
                    $usedPostIds[] = $post->id;
                    $slots[] = $this->formatArticle($post, curated: true);
                } else {
                    $slots[] = null;
                }
            }

            $missing = collect($slots)->filter()->count();
            if ($missing < $count) {
                if (($definition['fallback'] ?? '') === 'hub_leads') {
                    $hubSlugs = config('intelligence_brief.hub_slugs', []);

                    foreach ($slots as $index => $slot) {
                        if ($slot !== null) {
                            continue;
                        }

                        $hubSlug = $hubSlugs[$index] ?? null;

                        if (! $hubSlug) {
                            continue;
                        }

                        $post = $this->leadPostForHub($hubSlug);

                        if (! $post) {
                            continue;
                        }

                        $usedPostIds[] = $post->id;
                        $slots[$index] = $this->formatArticle($post, curated: false);
                    }
                } else {
                    $fallbackPosts = $this->fallbackPosts(
                        $definition['fallback'] ?? 'latest',
                        $hubSlug,
                        $count - $missing,
                        $usedPostIds
                    );

                    foreach ($slots as $index => $slot) {
                        if ($slot !== null) {
                            continue;
                        }

                        $post = $fallbackPosts->shift();

                        if (! $post) {
                            continue;
                        }

                        $usedPostIds[] = $post->id;
                        $slots[$index] = $this->formatArticle($post, curated: false);
                    }
                }
            }

            $sections[$sectionKey] = [
                'label' => $definition['label'],
                'description' => $definition['description'] ?? null,
                'items' => array_values(array_filter($slots)),
            ];
        }

        return [
            'page' => $page,
            'hub_slug' => $hubSlug,
            'sections' => $sections,
        ];
    }

    /** @param  list<int>  $excludeIds */
    private function fallbackPosts(string $strategy, ?string $hubSlug, int $limit, array $excludeIds): Collection
    {
        if ($limit < 1) {
            return collect();
        }

        $query = Post::published()
            ->with(['categories', 'tags'])
            ->latest('published_at');

        if ($excludeIds !== []) {
            $query->whereNotIn('id', $excludeIds);
        }

        if ($strategy === 'category_latest' && $hubSlug) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $hubSlug));
        }

        return $query->limit($limit)->get();
    }

    private function leadPostForHub(string $hubSlug): ?Post
    {
        $slot = LayoutSlot::query()
            ->where('page', 'hub')
            ->where('section', 'hero')
            ->where('position', 0)
            ->where('hub_slug', $hubSlug)
            ->with(['post.categories', 'post.tags'])
            ->first();

        $post = $slot?->post;

        if ($post && $post->status === PostStatus::Published->value) {
            return $post;
        }

        return Post::published()
            ->whereHas('categories', fn ($q) => $q->where('slug', $hubSlug))
            ->with(['categories', 'tags'])
            ->latest('published_at')
            ->first();
    }

    /** @return array<string, mixed> */
    private function formatArticle(Post $post, bool $curated): array
    {
        return [
            ...(new ArticleResource($post))->resolve(),
            'curated' => $curated,
        ];
    }

    /** @param  array<string, array<int, int|null>>  $assignments */
    public function syncHomepage(array $assignments): void
    {
        $this->syncPage('homepage', null, $assignments);
    }

    /** @param  array<string, array<int, int|null>>  $assignments */
    public function syncHub(string $hubSlug, array $assignments): void
    {
        $this->syncPage('hub', $hubSlug, $assignments);
    }

    /** @param  array<string, array<int, int|null>>  $assignments */
    private function syncPage(string $page, ?string $hubSlug, array $assignments): void
    {
        LayoutSlot::query()
            ->where('page', $page)
            ->when($hubSlug, fn ($q) => $q->where('hub_slug', $hubSlug))
            ->when(! $hubSlug, fn ($q) => $q->whereNull('hub_slug'))
            ->delete();

        foreach ($assignments as $section => $positions) {
            foreach ($positions as $position => $postId) {
                if (! $postId) {
                    continue;
                }

                LayoutSlot::create([
                    'page' => $page,
                    'section' => $section,
                    'position' => (int) $position,
                    'hub_slug' => $hubSlug,
                    'post_id' => $postId,
                ]);
            }
        }
    }
}
