<?php

declare(strict_types=1);

namespace App\Core\Application\Dto;

use Traversable;

trait DtoTrait
{
    public function toArray(): array
    {
        $vars = get_object_vars($this);

        foreach ($vars as &$var) {
            if ($var instanceof Dto) {
                $var = $var->toArray();
                continue;
            }

            if (is_iterable($var)) {
                $array = $this->iterableToArray($var);
                $callback = fn ($value) => $value instanceof Dto ? $value->toArray() : $value;
                $var = array_map($callback, $array);
            }
        }

        return $vars;
    }

    private function iterableToArray(iterable $iterable): array
    {
        if ($iterable instanceof Traversable) {
            return iterator_to_array($iterable);
        }

        if (is_array($iterable)) {
            return $iterable;
        }

        return (array) $iterable;
    }
}
