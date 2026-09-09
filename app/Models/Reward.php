<?php

namespace App\Models;

use Database\Factories\RewardFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description', 'point_cost', 'stock', 'image', 'is_active'])]
class Reward extends Model
{
    /** @use HasFactory<RewardFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'point_cost' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Redemptions made for this reward.
     *
     * @return HasMany<RewardRedemption, $this>
     */
    public function redemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class, 'reward_id');
    }
}
