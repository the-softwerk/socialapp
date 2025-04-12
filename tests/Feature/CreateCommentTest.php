<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_can_be_created_directly(): void
    {
        $user = User::factory()->create(['location' => 'Library']);
        $post = Post::create([
            'user_id' => $user->id,
            'content' => 'Original post',
            'location' => 'Room A',
        ]);

        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'body' => 'Test comment',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'body' => 'Test comment',
        ]);
    }

    public function test_comment_creation_fails_with_missing_fields(): void
    {
        $this->expectException(QueryException::class);

        Comment::create([]);
    }
}