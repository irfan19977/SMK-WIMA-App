<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $categories = ['Pendidikan', 'Kegiatan', 'Pengumuman', 'Prestasi', 'Lainnya'];

        // Create admin user if doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        for ($i = 0; $i < 20; $i++) {
            $title = $faker->sentence(6);
            $isPublished = $faker->boolean(80); // 80% chance to be published

            News::create([
                'id' => Str::uuid(), // Add UUID here
                'title' => $title,
                'slug' => Str::slug($title),
                'excerpt' => $faker->sentence(15),
                'content' => $faker->paragraphs(5, true),
                'image' => 'news/sample-' . $faker->numberBetween(1, 5) . '.jpg', // Assuming you have sample images
                'category' => $faker->randomElement($categories),
                'user_id' => $admin->id,
                'is_published' => $isPublished,
                'published_at' => $isPublished ? $faker->dateTimeBetween('-1 month', 'now') : null,
                'view_count' => $faker->numberBetween(0, 1000),
            ]);
        }
    }
}
