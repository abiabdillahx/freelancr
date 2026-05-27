<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $client = User::updateOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'Client Demo',
                'password' => Hash::make('password'),
                'role' => 'client',
                'bio' => 'Mahasiswa yang mencari jasa freelance kampus.',
            ],
        );

        $freelancer = User::updateOrCreate(
            ['email' => 'freelancer@example.com'],
            [
                'name' => 'Freelancer Demo',
                'password' => Hash::make('password'),
                'role' => 'freelancer',
                'bio' => 'Freelancer kampus untuk desain, web, dan tugas digital.',
            ],
        );

        $categories = collect([
            ['name' => 'Desain Grafis', 'slug' => 'desain-grafis'],
            ['name' => 'Pemrograman', 'slug' => 'pemrograman'],
            ['name' => 'Penerjemahan', 'slug' => 'penerjemahan'],
            ['name' => 'Akademik', 'slug' => 'akademik'],
        ])->mapWithKeys(fn (array $category) => [
            $category['slug'] => Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']],
            ),
        ]);

        $websiteService = Service::updateOrCreate(
            [
                'user_id' => $freelancer->id,
                'title' => 'Pembuatan Website Portfolio',
            ],
            [
                'category_id' => $categories['pemrograman']->id,
                'description' => 'Website portfolio responsive untuk mahasiswa, organisasi, atau tugas akhir.',
                'price' => 250000,
                'image_url' => null,
                'status' => 'active',
            ],
        );

        Service::updateOrCreate(
            [
                'user_id' => $freelancer->id,
                'title' => 'Desain Poster Event Kampus',
            ],
            [
                'category_id' => $categories['desain-grafis']->id,
                'description' => 'Desain poster digital untuk seminar, lomba, dan acara komunitas kampus.',
                'price' => 75000,
                'image_url' => null,
                'status' => 'active',
            ],
        );

        Service::updateOrCreate(
            [
                'user_id' => $freelancer->id,
                'title' => 'Terjemahan Abstrak Indonesia Inggris',
            ],
            [
                'category_id' => $categories['penerjemahan']->id,
                'description' => 'Terjemahan abstrak, ringkasan, dan dokumen pendek Indonesia ke Inggris.',
                'price' => 50000,
                'image_url' => null,
                'status' => 'active',
            ],
        );

        $completedOrder = Order::firstOrCreate(
            [
                'service_id' => $websiteService->id,
                'client_id' => $client->id,
                'status' => 'completed',
            ],
            [
                'note' => 'Butuh portfolio sederhana untuk daftar magang.',
            ],
        );

        Order::firstOrCreate(
            [
                'service_id' => $websiteService->id,
                'client_id' => $client->id,
                'status' => 'pending',
            ],
            [
                'note' => 'Mau diskusi tambahan halaman project.',
            ],
        );

        Review::firstOrCreate(
            ['order_id' => $completedOrder->id],
            [
                'rating' => 5,
                'comment' => 'Hasilnya rapi, cepat, dan sesuai brief.',
            ],
        );
    }
}
