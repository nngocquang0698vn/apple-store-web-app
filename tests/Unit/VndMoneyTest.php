<?php

namespace Tests\Unit;

use App\Support\VndMoney;
use PHPUnit\Framework\TestCase;

class VndMoneyTest extends TestCase
{
    public function test_formats_vnd_amount(): void
    {
        $this->assertSame('19.990.000 ₫', VndMoney::format(19_990_000));
        $this->assertSame('0 ₫', VndMoney::format(0));
    }
}
