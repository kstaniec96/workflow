<?php

declare(strict_types=1);

namespace tests\Feature;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    public function test_example(): void
    {
        $stack = [];
        $this->assertCount(0, $stack);

        $stack[] = 'bar';
        $this->assertSame('bar', $stack[count($stack) - 1]);
        $this->assertCount(1, $stack);

        $this->assertSame('bar', array_pop($stack));
        $this->assertCount(0, $stack);
    }
}
