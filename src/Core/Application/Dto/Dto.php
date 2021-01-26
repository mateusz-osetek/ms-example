<?php

declare(strict_types=1);

namespace App\Core\Application\Dto;

interface Dto
{
    public function toArray(): array;
}
