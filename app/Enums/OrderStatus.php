<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Chờ xử lý',
            self::Paid => 'Đã thanh toán',
            self::Shipped => 'Đang giao hàng',
            self::Completed => 'Hoàn thành',
            self::Cancelled => 'Đã hủy',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'yellow',
            self::Paid => 'purple',
            self::Shipped => 'indigo',
            self::Completed => 'green',
            self::Cancelled => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Pending => 'clock',
            self::Paid => 'credit-card',
            self::Shipped => 'truck',
            self::Completed => 'check-circle',
            self::Cancelled => 'times-circle',
        };
    }
}
