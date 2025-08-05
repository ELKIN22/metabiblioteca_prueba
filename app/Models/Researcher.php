<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Researcher extends Model
{
    protected $fillable = [
        'orcid',
        'given_names',
        'family_name',
        'primary_email',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con las palabras clave del investigador
     */
    public function keywords(): HasMany
    {
        return $this->hasMany(ResearcherKeyword::class);
    }

    /**
     * Obtener solo los valores de las palabras clave
     */
    public function getKeywordValuesAttribute(): array
    {
        return $this->keywords->pluck('keyword')->toArray();
    }

    /**
     * Buscar investigador por ORCID
     */
    public static function findByOrcid(string $orcid): ?self
    {
        return static::where('orcid', $orcid)->first();
    }
}
