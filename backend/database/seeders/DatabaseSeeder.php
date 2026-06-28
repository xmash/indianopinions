<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'editor@indianopinions.com'],
            ['name' => 'Desk Editor', 'password' => Hash::make('password'), 'role' => UserRole::Editor->value, 'is_active' => true]
        );

        User::updateOrCreate(
            ['email' => 'writer@indianopinions.com'],
            ['name' => 'Staff Writer', 'password' => Hash::make('password'), 'role' => UserRole::Writer->value, 'is_active' => true]
        );

        $categories = [
            ['name' => 'Politics', 'color' => '#AC0000', 'description' => 'Policy, governance, and political analysis.'],
            ['name' => 'Economy', 'color' => '#9A7A43', 'description' => 'Markets, development, and fiscal policy.'],
            ['name' => 'Foreign Affairs', 'color' => '#5D0000', 'description' => 'Diplomacy, geopolitics, and global relations.'],
            ['name' => 'Society', 'color' => '#6366f1', 'description' => 'Culture, community, and social change.'],
            ['name' => 'Technology', 'color' => '#14b8a6', 'description' => 'Innovation, digital policy, and industry.'],
            ['name' => 'Diaspora', 'color' => '#f59e0b', 'description' => 'Global Indians and transnational perspectives.'],
            ['name' => 'Opinion', 'color' => '#8b5cf6', 'description' => 'Editorial viewpoints and commentary.'],
            ['name' => 'Analysis', 'color' => '#343131', 'description' => 'In-depth strategic and investigative analysis.'],
            ['name' => 'Archive', 'color' => '#64748b', 'description' => 'Historical records and turning points that shaped the modern state.'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }

        foreach (['india', 'policy', 'economy', 'diaspora', 'technology', 'society', 'analysis', 'opinion'] as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }

        $this->call(DemoArticlesSeeder::class);
        $this->call(IntelligenceBriefSeeder::class);
    }
}
