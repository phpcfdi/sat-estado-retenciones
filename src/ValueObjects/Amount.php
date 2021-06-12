<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\ValueObjects;

use JsonSerializable;

final class Amount implements JsonSerializable
{
    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function newFromString(string $expression): self
    {
        return new self(floatval(preg_replace('/[^\d.]/', '', $expression)));
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function jsonSerialize(): float
    {
        return $this->value;
    }

    public function format(int $decimals = 2): string
    {
        return number_format($this->value, $decimals, '.', ',');
    }
}
