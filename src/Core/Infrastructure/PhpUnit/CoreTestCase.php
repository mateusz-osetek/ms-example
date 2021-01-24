<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\PhpUnit;

use PHPUnit\Framework\TestCase;

class CoreTestCase extends TestCase
{
    public function testGetTest(): void
    {
        self::assertEquals('test', 'test');
    }
}
