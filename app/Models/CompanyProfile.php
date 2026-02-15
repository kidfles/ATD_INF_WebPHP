<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyProfile extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'kvk_number',
        'brand_color',
        'custom_url_slug',
        'contract_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pageComponents(): HasMany
    {
        return $this->hasMany(PageComponent::class, 'company_id')->orderBy('order');
    }
}
