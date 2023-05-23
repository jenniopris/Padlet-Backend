<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Padlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_public',
        'user_id',
    ];

    /**
     * padlet is assigned to user (n:1)
     * @return BelongsTo
     */
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    /**
     * padlet has n entries
     * @return HasMany
     */
    public function entry() : HasMany {
        return $this->hasMany(Entry::class);
    }

    public function padletUser(): HasMany
    {
        return $this->hasMany(PadletUser::class);
    }
}
