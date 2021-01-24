<?php

declare(strict_types=1);

namespace App\Tests\Core\Domain\Model;

use App\Core\Domain\Model\Errors;
use App\Core\Domain\ValueObject\Foundation\Error;
use App\Core\Infrastructure\PhpUnit\CoreTestCase;

class ErrorsTest extends CoreTestCase
{
    public function testAdd(): void
    {
        $error = Error::create('Testing error', 'test-error', ['test' => 'error']);

        $errors = Errors::create();
        $errors->add('Testing error', 'test-error', ['test' => 'error']);

        self::assertEquals($errors->throwErrors()->first(), $error);
    }

    public function testHasErrors(): void
    {
        $errors = Errors::create();

        self::assertTrue($errors->hasErrors());
    }

    public function testHasNotErrors(): void
    {
        $errors = Errors::create();
        $errors->add('Testing error', 'test-error', ['test' => 'error']);

        self::assertFalse($errors->hasErrors());
    }

    public function testThrowErrors(): void
    {
        $errors = Errors::create();
        $errors->add('Testing error', 'test-error', ['test' => 'error']);
        $errors->add('Testing error #2');

        self::assertEquals(2, $errors->throwErrors()->count());
    }

    public function testThrowEmptyErrors(): void
    {
        $errors = Errors::create();
        self::assertNull($errors->throwErrors());
    }
}
