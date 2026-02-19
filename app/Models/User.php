<?php declare(strict_types=1);

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
use App\Enums\UserRole;

/**
 * User Model
 * 
 * Dit model vertegenwoordigt een geregistreerde gebruiker in het systeem. 
 * Het beheert authenticatie, rol-gebaseerde toegangscontrole en de kernrelaties 
 * (Advertenties, Reviews, Bestellingen).
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * De attributen die massaal toegewezen kunnen worden.
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
     * De attributen die verborgen moeten blijven voor serialisatie.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Haal de attributen op die gecast moeten worden.
     *
     * @return array<string, string> De lijst met casts.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    // -- Role Helpers --

    /**
     * Controleer of de gebruiker de 'admin' rol heeft.
     * 
     * @return bool True als de gebruiker een admin is.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Controleer of de gebruiker een zakelijke adverteerder is.
     * 
     * @return bool True als de gebruiker een zakelijk account heeft.
     */
    public function isBusinessAdvertiser(): bool
    {
        return $this->role === UserRole::BusinessSeller;
    }

    /**
     * Controleer of de gebruiker een particuliere adverteerder is.
     * 
     * @return bool True als de gebruiker een particulier account heeft.
     */
    public function isPrivateAdvertiser(): bool
    {
        return $this->role === UserRole::PrivateSeller;
    }

    // -- Relaties --

    /**
     * Het zakelijke/bedrijfsprofiel behorende bij de gebruiker.
     * Alleen relevant voor gebruikers met de 'business_ad' rol.
     * 
     * @return HasOne De relatie naar het bedrijfsprofiel.
     */
    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class);
    }

    /**
     * Advertenties geplaatst door deze gebruiker.
     * 
     * @return HasMany Een verzameling van advertenties.
     */
    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    /**
     * Verhuur-sessies gestart door deze gebruiker als huurder.
     * 
     * @return HasMany Een verzameling van verhuur-records.
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'renter_id');
    }

    /**
     * Biedingen geplaatst door deze gebruiker op verschillende advertenties.
     * 
     * @return HasMany Een verzameling van biedingen.
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * Advertenties die door deze gebruiker als favoriet zijn gemarkeerd.
     * Gebruikt de 'favorites' pivot-tabel.
     * 
     * @return BelongsToMany De favoriete advertenties.
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Advertisement::class, 'favorites')->withTimestamps();
    }

    /**
     * Reviews gegeven door deze gebruiker aan anderen (Verkopers of Producten).
     * 
     * @return HasMany Een verzameling van gegeven reviews.
     */
    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * Bestellingen waarbij deze gebruiker de koper is.
     * 
     * @return HasMany Een verzameling van aankopen.
     */
    public function orders(): HasMany {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Bestellingen waarbij deze gebruiker de verkoper is.
     * 
     * @return HasMany Een verzameling van verkopen.
     */
    public function sales(): HasMany {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /**
     * Reviews ontvangen door deze gebruiker.
     * Dit is een polymorfe relatie (MorphMany).
     * 
     * @return MorphMany Een verzameling van ontvangen reviews.
     */
    public function reviewsReceived(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Bedrijfslogica: Controleer of deze gebruiker een transactie heeft gehad met een andere gebruiker.
     * Dit wordt gebruikt voor de autorisatie van reviews (Verified Review check).
     * 
     * @param User $buyer De potentiële koper/huurder om te controleren.
     * @return bool True als er een transactie is gevonden.
     */
    public function hasSoldTo(User $buyer): bool
    {
        // Pad 1: Directe aankoop van een advertentie van het type 'verkoop'.
        $hasSold = $this->sales()->where('buyer_id', $buyer->id)->exists();
        
        // Pad 2: Huurovereenkomst via een advertentie van het type 'verhuur'.
        $hasRented = $this->advertisements()->whereHas('rentals', function($query) use ($buyer) {
            $query->where('renter_id', $buyer->id);
        })->exists();

        return $hasSold || $hasRented;
    }
}
