<?php

declare(strict_types=1);

namespace App\Core\Domain\ValueObject;

use ArrayAccess;
use Countable;
use Iterator;

interface ObjectCollection extends ValueObject, Countable, ArrayAccess, Iterator
{
    public static function create(string $class): ObjectCollection;

    public static function fromIterable(string $class, iterable $items): ObjectCollection;

    public static function fromMap(string $class, iterable $elements, callable $callback): ObjectCollection;

    public function toArray(): array;

    public function getClass(): string;

    public function isCollectionOf(string $class): bool;

    public function insert($item): ObjectCollection;

    public function remove($item): ObjectCollection;

    public function has($item): bool;

    public function merge(ObjectCollection $collection): ObjectCollection;

    public function isEmpty(): bool;

    public function count(): int;

    public function first();

    public function last();

    public function filter(callable $callback): ObjectCollection;

    public function map(callable $callback): array;
}
