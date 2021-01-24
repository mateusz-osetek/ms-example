<?php

declare(strict_types=1);

namespace App\Tests\Core\Domain\ValueObject\Foundation;

use App\Core\Domain\ValueObject\Foundation\Error;
use App\Core\Infrastructure\PhpUnit\CoreTestCase;

class ErrorTest extends CoreTestCase
{
    public function testEquals(): void
    {
        $error = Error::create('Testing error', 'error-test', ['test' => 'error']);
        $anotherError = Error::create('Testing error', 'error-test', ['test' => 'error']);
        self::assertTrue($error->equalsTo($anotherError));
    }

    public function testNotEquals(): void
    {
        $error = Error::create('Testing error', 'error-test', ['test' => 'error']);
        $anotherError = Error::create('Testing error #2');
        self::assertFalse($error->equalsTo($anotherError));
    }
}
