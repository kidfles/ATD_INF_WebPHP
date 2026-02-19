<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PageComponent Model
 * 
 * Dit model vertegenwoordigt een dynamisch UI-blok op de openbare profielpagina van een bedrijf.
 * Types zijn onder andere 'hero', 'text', en 'featured_ads'.
 */
class PageComponent extends Model
{
    /** @use HasFactory<\Database\Factories\PageComponentFactory> */
    use HasFactory;

    /**
     * De attributen die massaal toegewezen kunnen worden.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'component_type',
        'content',
        'order',
    ];

    /**
     * De attributen die gecast moeten worden naar een specifiek type.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Het bedrijfsprofiel waartoe dit component behoort.
     * 
     * @return BelongsTo Het bijbehorende bedrijfsprofiel.
     */
    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }
}
