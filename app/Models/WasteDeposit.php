<?php

namespace App\Models;

use Database\Factories\WasteDepositFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

#[Fillable([
    'user_id',
    'drop_point_id',
    'waste_type_id',
    'weight_kg',
    'photo',
    'status',
    'verified_by',
    'verified_at',
    'notes',
])]
class WasteDeposit extends Model
{
    /** @use HasFactory<WasteDepositFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * The user who deposited waste.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The drop point where waste was deposited.
     *
     * @return BelongsTo<DropPoint, $this>
     */
    public function dropPoint(): BelongsTo
    {
        return $this->belongsTo(DropPoint::class, 'drop_point_id');
    }

    /**
     * The category/type of waste deposited.
     *
     * @return BelongsTo<WasteType, $this>
     */
    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class, 'waste_type_id');
    }

    /**
     * The officer/admin who verified the deposit.
     *
     * @return BelongsTo<User, $this>
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * The point transaction credited for this deposit.
     *
     * @return MorphOne<PointTransaction, $this>
     */
    public function pointTransaction(): MorphOne
    {
        return $this->morphOne(PointTransaction::class, 'reference');
    }
}
