<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@indianopinions.com'],
            ['name' => 'Editor', 'password' => Hash::make('password')]
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
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }

        $tagNames = ['india', 'policy', 'economy', 'diaspora', 'technology', 'society', 'analysis', 'opinion'];
        foreach ($tagNames as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}
