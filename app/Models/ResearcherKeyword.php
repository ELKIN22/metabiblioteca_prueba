<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearcherKeyword extends Model
{
    protected $fillable = [
        'researcher_id',
        'keyword',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el investigador
     */
    public function researcher(): BelongsTo
    {
        return $this->belongsTo(Researcher::class);
    }
}
