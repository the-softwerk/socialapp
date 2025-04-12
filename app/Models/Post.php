<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Post
 *
 * @property int    $user_id
 * @property string $content
 * @property string $location
 */
class Post extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'image',
        'user_id',
        'content',
        'location',
        'approved',
        'created_at',
    ];

    /**
     * Get the user who created this post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all comments associated with this post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}