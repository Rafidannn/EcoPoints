<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'points_balance'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'points_balance' => 'integer',
        ];
    }

    /**
     * Check if user has admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Check if user has petugas role.
     */
    public function isPetugas(): bool
    {
        return $this->role === UserRole::PETUGAS;
    }

    /**
     * Check if user has regular user role.
     */
    public function isUser(): bool
    {
        return $this->role === UserRole::USER;
    }

    /**
     * Check if user has any of the given roles.
     *
     * @param  UserRole|string|array<int, UserRole|string>  $roles
     */
    public function hasRole(UserRole|string|array $roles): bool
    {
        $roleList = is_array($roles) ? $roles : [$roles];

        foreach ($roleList as $r) {
            $value = $r instanceof UserRole ? $r->value : $r;
            if ($this->role?->value === $value || $this->role === $r) {
                return true;
            }
        }

        return false;
    }

    /**
     * Waste deposits submitted by this user.
     *
     * @return HasMany<WasteDeposit, $this>
     */
    public function wasteDeposits(): HasMany
    {
        return $this->hasMany(WasteDeposit::class, 'user_id');
    }

    /**
     * Waste deposits verified by this user (as petugas/admin).
     *
     * @return HasMany<WasteDeposit, $this>
     */
    public function verifiedDeposits(): HasMany
    {
        return $this->hasMany(WasteDeposit::class, 'verified_by');
    }

    /**
     * Point transactions history.
     *
     * @return HasMany<PointTransaction, $this>
     */
    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class, 'user_id');
    }

    /**
     * Reward redemptions requested by this user.
     *
     * @return HasMany<RewardRedemption, $this>
     */
    public function rewardRedemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class, 'user_id');
    }
}
