<?php

declare(strict_types=1);

namespace App\Message;

class ResponseMessage // TODO: This should be added to contracts / external schema
{
    public function __construct(private readonly int $value)
    {
    }
    public function getValue(): int
    {
        return $this->value;
    }
}