<?php

declare(strict_types=1);

namespace App\Core\Domain\ValueObject\Foundation;

use App\Core\Domain\ValueObject\Error as ErrorInterface;
use App\Core\Domain\ValueObject\ValueObject;

final class Error implements ValueObject, ErrorInterface
{
    private string $message;

    private string $code;

    private array $data;

    public static function create(string $message, string $code = '', array $data = []): self
    {
        return new self($message, $code, $data);
    }

    public function __construct(string $message, string $code, array $data)
    {
        $this->message = $message;
        $this->code = $code;
        $this->data = $data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function equalsTo(ValueObject $another): bool
    {
        if ($another instanceof self) {
            return $another->getMessage() === $this->getMessage()
                && $another->getCode() === $this->getCode()
                && $another->getData() === $this->getData();
        }

        return false;
    }
}
