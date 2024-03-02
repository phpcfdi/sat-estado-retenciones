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

    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
