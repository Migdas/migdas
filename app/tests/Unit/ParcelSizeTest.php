<?php

namespace Tests\Unit;

use App\Support\ParcelSize;
use PHPUnit\Framework\TestCase;

class ParcelSizeTest extends TestCase
{
    public function test_size_is_suggested_from_dimensions_with_packing_margin(): void
    {
        $this->assertNull(ParcelSize::fromDimensions(10, null, 5));
        $this->assertSame('A', ParcelSize::fromDimensions(30, 20, 5));
        $this->assertSame('A', ParcelSize::fromDimensions(5, 30, 60));
        $this->assertSame('B', ParcelSize::fromDimensions(30, 20, 7));
        $this->assertSame('C', ParcelSize::fromDimensions(30, 30, 30));
        $this->assertSame('D', ParcelSize::fromDimensions(70, 10, 10));
    }

    public function test_largest_size_wins(): void
    {
        $this->assertSame('A', ParcelSize::largest([]));
        $this->assertSame('C', ParcelSize::largest(['A', 'C', 'B']));
        $this->assertSame('D', ParcelSize::largest(['D', 'A']));
    }
}
