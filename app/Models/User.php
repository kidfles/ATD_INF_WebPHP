<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    // -- Role Helpers --

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBusinessAdvertiser(): bool
    {
        return $this->role === 'business_ad';
    }

    public function isPrivateAdvertiser(): bool
    {
        return $this->role === 'private_ad';
    }

    // -- Relationships --

    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'renter_id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Advertisement::class, 'favorites')->withTimestamps();
    }

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function orders(): HasMany {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function sales(): HasMany {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function reviewsReceived(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function hasSoldTo(User $buyer): bool
    {
        // Check Orders (Sales)
        $hasSold = $this->sales()->where('buyer_id', $buyer->id)->exists();
        
        // Check Rentals (via Advertisements)
        $hasRented = $this->advertisements()->whereHas('rentals', function($query) use ($buyer) {
            $query->where('renter_id', $buyer->id);
        })->exists();

        return $hasSold || $hasRented;
    }
}
