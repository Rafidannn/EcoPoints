<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case PETUGAS = 'petugas';
    case USER = 'user';

    /**
     * Get user-friendly label for the role.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::PETUGAS => 'Petugas Drop Point',
            self::USER => 'Nasabah / User',
        };
    }

    /**
     * Get all values as an array.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
