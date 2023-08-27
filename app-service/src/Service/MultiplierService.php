<?php

declare(strict_types=1);

namespace App\Service;

class MultiplierService
{
    public function multiply(int $a): int
    {
        return $a * 2;
    }
}