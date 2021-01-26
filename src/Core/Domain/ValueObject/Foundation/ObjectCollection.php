<?php

declare(strict_types=1);

namespace App\Core\Domain\ValueObject\Foundation;

use App\Core\Domain\ValueObject\ObjectCollection as ObjectCollectionInterface;
use App\Core\Domain\ValueObject\ValueObject;

final class ObjectCollection implements ObjectCollectionInterface
{
    private array $items;

    private string $class;

    private ?int $index;

    public static function create(string $class): ObjectCollectionInterface
    {
        return new self($class, []);
    }

    public static function fromIterable(string $class, iterable $items): ObjectCollectionInterface
    {
        $acceptableItems = [];
        $itemsArray = [];
        array_push($itemsArray, ...$items);

        foreach ($itemsArray as $key => $item) {
            if ($item instanceof $class) {
                $acceptableItems[] = $item;
            }
        }

        return new self($class, $acceptableItems);
    }

    public static function fromMap(string $class, iterable $elements, callable $callback): ObjectCollectionInterface
    {
        $acceptableItems = [];
        $itemsArray = [];
        array_push($itemsArray, ...$elements);

        foreach ($itemsArray as $key => $item) {
            $item = $callback($item);
            if ($item instanceof $class) {
                $acceptableItems[] = $item;
            }
        }

        return new self($class, $acceptableItems);
    }

    public function __construct(string $class, array $items)
    {
        $this->class = $class;
        $this->items = $items;
        $this->rewind();
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function isCollectionOf(string $class): bool
    {
        return $this->class === $class;
    }

    public function insert($item): ObjectCollectionInterface
    {
        if (false === is_object($item)) {
            return $this;
        }

        if ($item instanceof $this->class) {
            $this->items[] = $item;
        }

        return $this;
    }

    public function remove($item): ObjectCollectionInterface
    {
        foreach ($this->items as $key => $collectionItem) {
            if ($this->areEqualItems($item, $collectionItem)) {
                unset($this->items[$key]);
                $this->rewind();
            }
        }

        return $this;
    }

    public function has($item): bool
    {
        foreach ($this->items as $key => $collectionItem) {
            if ($this->areEqualItems($item, $collectionItem)) {
                return true;
            }
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return [] === $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function filter(callable $callback): ObjectCollectionInterface
    {
        return self::fromIterable(
            $this->class,
            array_filter($this->toArray(), $callback)
        );
    }

    public function map(callable $callback): array
    {
        $result = [];
        foreach ($this->items as $item) {
            $result[] = $callback($item);
        }

        return $result;
    }

    /**
     * @param ValueObject|self $another
     */
    public function equalsTo($another): bool
    {
        return $this === $another;
    }

    public function current()
    {
        return $this->items[$this->index];
    }

    public function next()
    {
        return $this->items[++$this->index] ?? null;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->index]);
    }

    public function rewind(): void
    {
        $this->index = $this->isEmpty() ? null : 0;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        if (false === $this->offsetExists($offset)) {
            return null;
        }

        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (false === is_object($offset)) {
            return;
        }

        if ($this->class === get_class($value)) {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        if (false === $this->offsetExists($offset)) {
            return;
        }

        unset($this->items[$offset]);
    }

    private function areEqualItems(object $object, $anotherObject): bool
    {
        return $object === $anotherObject;
    }

    public function first()
    {
        if ([] === $this->items) {
            return null;
        }

        return $this->items[array_key_first($this->items)];
    }

    public function last()
    {
        if ([] === $this->items) {
            return null;
        }

        return $this->items[array_key_last($this->items)];
    }

    public function merge($collection): ObjectCollectionInterface
    {
        if (false === is_object($collection)) {
            return $this;
        }

        foreach ($collection as $item) {
            $this->insert($item);
        }

        return $this;
    }
}
