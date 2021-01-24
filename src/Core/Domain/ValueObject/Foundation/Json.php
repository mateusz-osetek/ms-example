<?php

declare(strict_types=1);

namespace App\Core\Domain\ValueObject\Foundation;

use App\Core\Domain\ValueObject\ValueObject;

final class Json implements ValueObject
{
    private string $json;

    public static function fromString(string $string): ?self
    {
        try {
            json_decode($string, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return null;
        }

        return new self($string);
    }

    public static function fromArray(array $array): ?self
    {
        try {
            return new self(json_encode($array, JSON_THROW_ON_ERROR));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function __construct(string $json)
    {
        $this->json = $json;
    }

    public function toString(): string
    {
        return $this->json;
    }

    public function toArray(): array
    {
        return json_decode($this->json, true, 512, JSON_THROW_ON_ERROR);
    }

    public function equalsTo(ValueObject $another): bool
    {
        if ($another instanceof self) {
            return $another->toString() === $this->toString();
        }

        return false;
    }
}
