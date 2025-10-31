<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Card = 'card';
    case Bank = 'bank';
    case COD = 'cod';
    case Wallet = 'wallet';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::Card => 'Thẻ tín dụng',
            self::Bank => 'Chuyển khoản ngân hàng',
            self::COD => 'Thanh toán khi nhận hàng',
            self::Wallet => 'Ví điện tử',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Card => 'credit-card',
            self::Bank => 'university',
            self::COD => 'money-bill-wave',
            self::Wallet => 'wallet',
        };
    }
}