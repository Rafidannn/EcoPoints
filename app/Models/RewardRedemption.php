<?php

namespace App\Models;

use Database\Factories\RewardRedemptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable([
    'user_id',
    'reward_id',
    'points_used',
    'status',
    'notes',
])]
class RewardRedemption extends Model
{
    /** @use HasFactory<RewardRedemptionFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points_used' => 'integer',
        ];
    }

    /**
     * The user who redeemed the reward.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The reward redeemed.
     *
     * @return BelongsTo<Reward, $this>
     */
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class, 'reward_id');
    }

    /**
     * The point transaction debited for this redemption.
     *
     * @return MorphOne<PointTransaction, $this>
     */
    public function pointTransaction(): MorphOne
    {
        return $this->morphOne(PointTransaction::class, 'reference');
    }
}
