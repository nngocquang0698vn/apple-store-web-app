<?php

namespace Tests\Unit;

use App\Enums\OrderStatus;
use PHPUnit\Framework\TestCase;

class OrderStatusTest extends TestCase
{
    public function test_pending_can_move_to_confirmed_or_cancelled_only_via_cancel_action(): void
    {
        $this->assertTrue(OrderStatus::Pending->canTransitionTo(OrderStatus::Confirmed));
        $this->assertFalse(OrderStatus::Pending->canTransitionTo(OrderStatus::Cancelled));
        $this->assertTrue(OrderStatus::Pending->canBeCancelled());
    }

    public function test_completed_and_cancelled_are_terminal(): void
    {
        $this->assertTrue(OrderStatus::Completed->isTerminal());
        $this->assertTrue(OrderStatus::Cancelled->isTerminal());
        $this->assertSame([], OrderStatus::Completed->allowedTransitions());
    }

    public function test_valid_forward_transitions(): void
    {
        $this->assertTrue(OrderStatus::Confirmed->canTransitionTo(OrderStatus::Shipping));
        $this->assertTrue(OrderStatus::Shipping->canTransitionTo(OrderStatus::Completed));
        $this->assertFalse(OrderStatus::Shipping->canBeCancelled());
    }
}
