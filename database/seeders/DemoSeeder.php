<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Seeds the database with demo data for UI filtering and realistic time-based activity.
     *
     * Each user receives:
     *  - One post created today
     *  - Two additional posts created within the past 1 to 10 days
     * Each post receives one comment created between the post date and now.
     */
    public function run(): void
    {
        $locations = ['Room A', 'Zone 1', 'Library', 'Hall 3', 'North Zone'];

        User::factory(10)->create()->each(function ($user) use ($locations) {
            $user->update([
                'location'   => fake()->randomElement($locations),
                'created_at' => Carbon::now(),
            ]);

            // Post created today

            $todayPost = Post::factory()->create([
                'user_id'    => $user->id,
                'location'   => $user->location,
                'created_at' => Carbon::now(),
            ]);

            Comment::factory()->create([
                'user_id'    => $user->id,
                'post_id'    => $todayPost->id,
                'created_at' => Carbon::now(),
            ]);

            // Two additional posts with comments, dated 1–10 days ago

            collect(range(1, 2))->each(function () use ($user) {
                $daysAgo = random_int(1, 10);

                $post = Post::factory()->create([
                    'user_id'    => $user->id,
                    'location'   => $user->location,
                    'created_at' => Carbon::now()->subDays($daysAgo),
                ]);

                Comment::factory()->create([
                    'user_id'    => $user->id,
                    'post_id'    => $post->id,
                    'created_at' => Carbon::now()->subDays(random_int(0, $daysAgo)),
                ]);
            });
        });
    }
}
