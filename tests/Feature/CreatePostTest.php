<?php

namespace Tests\Feature;

use App\Models\Post;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_can_be_created_directly()
    {
        $user = User::factory()->create(['location' => 'Room A']);

        Post::create([
            'user_id' => $user->id,
            'content' => 'Test content',
            'location' => 'Room A',
        ]);

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'content' => 'Test content',
            'location' => 'Room A',
        ]);
    }

    public function test_post_creation_fails_with_missing_fields(): void
    {
        $this->expectException(QueryException::class);

        Post::create([]);
    }

    public function test_post_can_be_approved()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'approved' => false,
        ]);

        $post->approved = true;
        $post->save();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'approved' => true,
        ]);
    }
}
