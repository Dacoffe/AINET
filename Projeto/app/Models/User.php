<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'type',
        'password',
        'blocked',
        'gender',
        'photo',
        'nif',
        'default_delivery_address'
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function firstLastInitial(): string
    {
        $allNames = Str::of($this->name)
            ->explode(' ');
        $firstName = $allNames->first();
        $lastName = $allNames->count() > 1 ? $allNames->last() : '';
        return Str::of($firstName)->substr(0, 1)
            ->append(' ')
            ->append(Str::of($lastName)->substr(0, 1));
    }

    public function firstLastName(): string
    {
        $allNames = Str::of($this->name)
            ->explode(' ');
        $firstName = $allNames->first();
        $lastName = $allNames->count() > 1 ? $allNames->last() : '';
        return Str::of($firstName)
            ->append(' ')
            ->append(Str::of($lastName));
    }

    public function getPhotoFullUrlAttribute()
    {
        if ($this->photo && Storage::disk('public')->exists("users/{$this->photo}")) {
            return asset("storage/users/{$this->photo}");
        } else {
            return asset("storage/users/anonymous.png");
        }
    }

    public function supply_order(): HasMany
    {
        return $this->hasMany(Supply_Order::class, 'registered_by_user_id', 'id');
    }

    public function stock_adjustment(): HasMany
    {
        return $this->hasMany(Stock_Adjustment::class, 'registered_by_user_id', 'id');
    }

    public function card(): HasOne
    {
        return $this->hasOne(Card::class, 'id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'member_id', 'id');
    }

    public function isBoardMember()
    {
        return $this->type === 'board';
    }

    public function isClubMember()
    {
        return $this->type === 'member';
    }

    public function isEmployee()
    {
        return $this->type === 'employee';
    }

    public function mailConfirmed()
    {
        return $this->email_verified_at !== null;
    }
}
