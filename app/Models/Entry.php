<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Model
{
    use HasFactory;

    protected $table = 'entries';

    protected $fillable = [
        'padlet_id',
        'user_id',
        'type',
        'name',
        'content',
    ];

    /**
     * entry is assigned to padlet (n:1)
     * @return BelongsTo
     */
    public function padlet() : BelongsTo
    {
        return $this->belongsTo(Padlet::class);
    }

    /**
     * entry is assigned to user (n:1)
     * @return BelongsTo
     */
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * entry has n comments
     * @return HasMany
     */
    public function comment() : HasMany {
        return $this->hasMany(Comment::class);
    }

    /**
     * entry has n ratings
     * @return HasMany
     */
    public function rating() : HasMany {
        return $this->hasMany(Rating::class);
    }
}
