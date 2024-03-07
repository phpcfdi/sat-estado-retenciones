<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\ValueObjects;

use JsonSerializable;
use PhpCfdi\SatEstadoRetenciones\Internal\EnumIsTypeTrait;

/**
 * @method bool isIncluded()
 * @method bool isExcluded()
 * @method bool isUnknown()
 */
enum StatusEfos implements JsonSerializable
{
    use EnumIsTypeTrait;

    case Included;
    case Excluded;
    case Unknown;

    public static function fromValue(string $value): self
    {
        return match ($value) {
            '100' => self::Included,
            '200' => self::Excluded,
            default => self::Unknown,
        };
    }

    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
