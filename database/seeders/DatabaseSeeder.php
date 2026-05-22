<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            User::factory()->make([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])->toArray()
        );

        collect([
            ['name' => 'Desain Grafis', 'slug' => 'desain-grafis'],
            ['name' => 'Pemrograman', 'slug' => 'pemrograman'],
            ['name' => 'Penerjemahan', 'slug' => 'penerjemahan'],
            ['name' => 'Akademik', 'slug' => 'akademik'],
        ])->each(fn (array $category) => Category::firstOrCreate(
            ['slug' => $category['slug']],
            ['name' => $category['name']]
        ));
    }
}
