<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_must_be_unique(): void
    {
        $email = 'test@example.com';

        User::create([
            'name' => 'First',
            'email' => $email,
            'password' => bcrypt('secret'),
            'location' => 'Room A',
        ]);

        $this->expectException(QueryException::class);

        User::create([
            'name' => 'Second',
            'email' => $email,
            'password' => bcrypt('secret'),
            'location' => 'Zone 1',
        ]);
    }

    public function test_location_is_required(): void
    {
        $this->expectException(QueryException::class);

        User::create([
            'name' => 'No Location',
            'email' => 'noloc@example.com',
            'password' => bcrypt('secret'),
        ]);
    }

    public function test_user_avatar_can_be_uploaded(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg', 128, 128)->size(200);

        $user->update([
            'avatar' => $file->store('avatars', 'public'),
        ]);

        Storage::disk('public')->assertExists($user->avatar);
        $this->assertNotNull($user->fresh()->avatar);
    }
}