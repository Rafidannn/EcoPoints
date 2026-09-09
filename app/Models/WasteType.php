<?php

namespace App\Models;

use Database\Factories\WasteTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'unit_price_per_kg', 'points_per_kg', 'description', 'is_active'])]
class WasteType extends Model
{
    /** @use HasFactory<WasteTypeFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unit_price_per_kg' => 'decimal:2',
            'points_per_kg' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Waste deposits of this type.
     *
     * @return HasMany<WasteDeposit, $this>
     */
    public function wasteDeposits(): HasMany
    {
        return $this->hasMany(WasteDeposit::class, 'waste_type_id');
    }
}
