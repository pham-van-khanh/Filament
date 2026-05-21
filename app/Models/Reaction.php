<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'ip_address',
        'session_id',
        'reaction_type',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
