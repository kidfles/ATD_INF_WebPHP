<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageComponent extends Model
{
    /** @use HasFactory<\Database\Factories\PageComponentFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'component_type',
        'content',
        'order',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }
}
