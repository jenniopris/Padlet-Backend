<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * user has n padlets
     * @return HasMany
     */
    public function padlet() : HasMany{
        return $this->hasMany(Padlet::class);
    }

    /**
     * user has n entries
     * @return HasMany
     */
    public function entry() : HasMany {
        return $this->hasMany(Entry::class);
    }

    /**
     * user has n comments
     * @return HasMany
     */
    public function comment() : HasMany {
        return $this->hasMany(Comment::class);
    }

    /**
     * user has n ratings
     * @return HasMany
     */
    public function rating() : HasMany {
        return $this->hasMany(Rating::class);
    }
}
