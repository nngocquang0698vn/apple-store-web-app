<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Shipping = 'shipping';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Chờ xác nhận',
            self::Confirmed => 'Đã xác nhận',
            self::Shipping => 'Đang giao',
            self::Completed => 'Hoàn thành',
            self::Cancelled => 'Đã hủy',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Completed, self::Cancelled], true);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this, [self::Pending, self::Confirmed], true);
    }

    /**
     * @return list<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Pending => [self::Confirmed],
            self::Confirmed => [self::Shipping],
            self::Shipping => [self::Completed],
            self::Completed, self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }
}
