<?php

declare(strict_types=1);

namespace App\Tests\Core\Domain\ValueObject;

use App\Core\Domain\ValueObject\ValueObject;

final class Test implements ValueObject
{
    private string $property;

    public static function create(string $property): self
    {
        return new self($property);
    }

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    public function toString(): string
    {
        return $this->property;
    }

    public function equalsTo(ValueObject $another): bool
    {
        if ($another instanceof self) {
            return $another->toString() === $this->toString();
        }

        return false;
    }
}
