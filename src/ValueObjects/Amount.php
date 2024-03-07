<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\ValueObjects;

use JsonSerializable;
use Stringable;

final readonly class Amount implements JsonSerializable, Stringable
{
    public function __construct(public float $value)
    {
    }

    public static function newFromString(string $expression): self
    {
        return new self(floatval(preg_replace('/[^\d.]/', '', $expression)));
    }

    public function jsonSerialize(): float
    {
        return $this->value;
    }

    public function format(int $decimals = 2): string
    {
        return number_format($this->value, $decimals, '.', ',');
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
