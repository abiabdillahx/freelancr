<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Categories
        $categories = [
            ['name' => 'Design', 'slug' => 'design'],
            ['name' => 'Programming', 'slug' => 'programming'],
            ['name' => 'Writing', 'slug' => 'writing'],
            ['name' => 'Video & Animation', 'slug' => 'video-animation'],
            ['name' => 'Music & Audio', 'slug' => 'music-audio'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 2. Create Freelancers
        $freelancers = [
            [
                'name' => 'Thoriq',
                'email' => 'thoriq@test.com',
                'password' => Hash::make('password123'),
                'role' => 'freelancer',
                'bio' => 'UI/UX Designer & Web Developer Mahasiswa. Fokus di pengerjaan landing page dan desain interface yang rapi.',
            ],
            [
                'name' => 'Nadia Putri',
                'email' => 'nadia@test.com',
                'password' => Hash::make('password123'),
                'role' => 'freelancer',
                'bio' => 'Content Writer & Copywriter. Membantu tugas essay, artikel, dan copywriting campaign produk.',
            ],
            [
                'name' => 'Bagas Wardana',
                'email' => 'bagas@test.com',
                'password' => Hash::make('password123'),
                'role' => 'freelancer',
                'bio' => 'Video Editor. Ahli dalam editing video seminar, tugas kuliah, dan konten TikTok/Instagram.',
            ],
        ];

        foreach ($freelancers as $f) {
            User::updateOrCreate(['email' => $f['email']], $f);
        }

        // 3. Create a Client
        $client = User::updateOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Budi Santoso',
                'email' => 'client@test.com',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'bio' => 'Mahasiswa aktif yang sering butuh bantuan jasa digital cepat.',
            ]
        );

        // 4. Create Services
        $designCat = Category::where('slug', 'design')->first();
        $writeCat = Category::where('slug', 'writing')->first();
        $videoCat = Category::where('slug', 'video-animation')->first();

        $thoriq = User::where('email', 'thoriq@test.com')->first();
        $nadia = User::where('email', 'nadia@test.com')->first();
        $bagas = User::where('email', 'bagas@test.com')->first();

        $services = [
            [
                'user_id' => $thoriq->id,
                'category_id' => $designCat->id,
                'title' => 'Desain Landing Page Portfolio',
                'description' => 'Jasa pembuatan desain UI landing page portfolio mahasiswa. Hasil berupa file Figma yang siap didevelop.',
                'price' => 150000,
                'status' => 'active',
            ],
            [
                'user_id' => $nadia->id,
                'category_id' => $writeCat->id,
                'title' => 'Jasa Penulisan Artikel / Blog',
                'description' => 'Menulis artikel 500-1000 kata untuk tugas atau blog. Riset mendalam dan anti-plagiasi.',
                'price' => 50000,
                'status' => 'active',
            ],
            [
                'user_id' => $bagas->id,
                'category_id' => $videoCat->id,
                'title' => 'Edit Video Tugas Seminar',
                'description' => 'Editing video dokumentasi seminar atau tugas presentasi. Sudah termasuk subtitle dan backsound.',
                'price' => 100000,
                'status' => 'active',
            ],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(['title' => $s['title'], 'user_id' => $s['user_id']], $s);
        }

        // 5. Create some Orders & Reviews
        $service1 = Service::first();
        if ($service1) {
            $order = Order::updateOrCreate(
                ['client_id' => $client->id, 'service_id' => $service1->id],
                [
                    'client_id' => $client->id,
                    'service_id' => $service1->id,
                    'status' => 'completed',
                    'note' => 'Butuh buat tugas akhir minggu depan.',
                ]
            );

            Review::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'order_id' => $order->id,
                    'rating' => 5,
                    'comment' => 'Hasilnya sangat memuaskan, desainnya modern dan rapi!',
                ]
            );
        }
    }
}
