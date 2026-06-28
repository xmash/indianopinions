<?php

namespace Database\Seeders;

use App\Models\IntelligenceBrief;
use App\Models\IntelligenceBriefItem;
use App\Services\IntelligenceBriefCompositionService;
use Illuminate\Database\Seeder;

class IntelligenceBriefSeeder extends Seeder
{
    public function run(): void
    {
        /** @var list<array{0: string, 1: list<string>}> */
        $editions = [
            ['2026-06-22', ['water-agriculture-fiscal-crisis-gangetic-plain', 'institutional-memory-india-underrated-asset']],
            ['2026-06-23', ['letter-next-generation-policy-debaters', 'indian-diaspora-soft-power-britain-america']],
            ['2026-06-24', ['semiconductor-fabs-indian-tech-power', 'middle-corridor-india-eurasian-gambit']],
            ['2026-06-25', ['upi-global-digital-public-infrastructure', 'india-quad-commitments-strategic-autonomy']],
            ['2026-06-26', ['manufacturing-second-act-pli-schemes', 'mapping-india-electoral-geography']],
            ['2026-06-27', ['coalition-calculus-monsoon-session', 'urban-migration-tier-two-cities']],
        ];

        $composition = app(IntelligenceBriefCompositionService::class);
        $editionCount = count($editions);

        foreach ($editions as $editionIndex => [$date, $leadSlugs]) {
            $this->seedEdition(
                $date,
                $composition->leadsFromPosts($leadSlugs),
                $composition->hubItemsForEdition($editionIndex, $editionCount),
            );
        }
    }

    /**
     * @param  list<array{headline: string, blurb: string, post_id: int|null}>  $leads
     * @param  list<array{hub_slug: string, blurb: string, post_id: int|null, position: int}>  $hubs
     */
    private function seedEdition(string $date, array $leads, array $hubs): void
    {
        $brief = IntelligenceBrief::updateOrCreate(
            ['edition_date' => $date],
            ['published_at' => "{$date} 06:00:00"]
        );

        $brief->items()->delete();

        foreach ($leads as $position => $lead) {
            IntelligenceBriefItem::create([
                'intelligence_brief_id' => $brief->id,
                'type' => 'lead',
                'position' => $position,
                'headline' => $lead['headline'],
                'blurb' => $lead['blurb'],
                'post_id' => $lead['post_id'],
            ]);
        }

        foreach ($hubs as $hub) {
            IntelligenceBriefItem::create([
                'intelligence_brief_id' => $brief->id,
                'type' => 'hub',
                'hub_slug' => $hub['hub_slug'],
                'position' => $hub['position'],
                'blurb' => $hub['blurb'],
                'post_id' => $hub['post_id'],
            ]);
        }
    }
}
