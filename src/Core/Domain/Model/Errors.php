<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use App\Core\Domain\ValueObject\Error;
use App\Core\Domain\ValueObject\Foundation\Error as ErrorVO;
use App\Core\Domain\ValueObject\Foundation\ObjectCollection;
use App\Core\Domain\ValueObject\ObjectCollection as ObjectCollectionInterface;

class Errors implements Error, Entity
{
    private ObjectCollectionInterface $errors;

    public static function create(): self
    {
        return new self();
    }

    public function __construct()
    {
        $this->errors = ObjectCollection::create(Error::class);
    }

    public function add(string $message, string $code = 'core-error', array $data = []): void
    {
        $this->errors = $this->errors->insert(ErrorVO::create($message, $code, $data));
    }

    public function hasErrors(): bool
    {
        return $this->errors->isEmpty();
    }

    public function throwErrors(): ?ObjectCollectionInterface
    {
        if ($this->errors->isEmpty()) {
            return null;
        }

        return $this->errors;
    }

    public function equalsTo(Entity $entity): bool
    {
        if ($entity instanceof self) {
            return $entity->throwErrors()->equalsTo($this->throwErrors());
        }

        return false;
    }
}
