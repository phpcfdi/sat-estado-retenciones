<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\ValueObjects;

use JsonSerializable;
use PhpCfdi\SatEstadoRetenciones\Internal\EnumIsTypeTrait;

/**
 * @method bool isActive()
 * @method bool isCancelled()
 * @method bool isUnknown()
 */
enum StatusDocument implements JsonSerializable
{
    use EnumIsTypeTrait;

    case Active;
    case Cancelled;
    case Unknown;

    public static function fromValue(string $value): self
    {
        return match ($value) {
            'Vigente' => self::Active,
            'Cancelado' => self::Cancelled,
            default => self::Unknown,
        };
    }

    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
