<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'location',
        'created_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Get all posts created by this user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    /**
     * Get all comments written by this user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
