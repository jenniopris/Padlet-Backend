<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_id',
        'user_id',
        'rating',
    ];

    /**
     * rating is assigned to entry (1:1)
     * @return BelongsTo
     */
    public function entry() {
        return $this->belongsTo(Entry::class);
    }

    /**
     * rating is assigned to user (1:1)
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }
}
