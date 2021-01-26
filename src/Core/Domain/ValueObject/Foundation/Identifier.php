<?php

declare(strict_types=1);

namespace App\Core\Domain\ValueObject\Foundation;

use App\Core\Domain\ValueObject\ValueObject;
use Ramsey\Uuid\Uuid;

final class Identifier implements ValueObject
{
    private string $id;

    public static function random(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function equalsTo(ValueObject $another): bool
    {
        if ($another instanceof self) {
            return $another->toString() === $this->toString();
        }

        return false;
    }
}
