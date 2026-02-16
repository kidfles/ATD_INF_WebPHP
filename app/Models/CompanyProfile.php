<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * CompanyProfile Model
 * 
 * Dit model vertegenwoordigt een zakelijk profiel voor een gebruiker. Het beheert branding, 
 * KvK-details, contractstatus en gekoppelde paginacomponenten.
 */
class CompanyProfile extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyProfileFactory> */
    use HasFactory;

    /**
     * De attributen die massaal toegewezen kunnen worden.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_name',
        'kvk_number',
        'brand_color',
        'custom_url_slug',
        'contract_status',
        'contract_file_path',
        'wear_and_tear_policy',
        'wear_and_tear_value',
    ];

    /**
     * De attributen die gecast moeten worden.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'wear_and_tear_value' => 'decimal:2',
        ];
    }

    /**
     * De gebruiker (eigenaar) die gekoppeld is aan dit bedrijfsprofiel.
     * 
     * @return BelongsTo De eigenaar van het bedrijf.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * De visuele componenten (Hero, Tekst, etc.) die de openbare pagina van het bedrijf opbouwen.
     * Gesorteerd op de 'order' kolom.
     * 
     * @return HasMany Een verzameling van paginacomponenten.
     */
    public function pageComponents(): HasMany
    {
        return $this->hasMany(PageComponent::class, 'company_id')->orderBy('order');
    }
}
