<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PadletUser extends Model
{
    use HasFactory;

    protected $table = 'padlet_user';

    protected $fillable = [
        'user_id',
        'padlet_id',
        'role',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function padlet(): BelongsTo
    {
        return $this->belongsTo(Padlet::class);
    }
}
