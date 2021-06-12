<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\ValueObjects;

use Eclipxe\Enum\Enum;
use JsonSerializable;

/**
 * @method static self active()
 * @method static self cancelled()
 * @method static self unknown()
 *
 * @method bool isActive()
 * @method bool isCancelled()
 * @method bool isUnknown()
 */
class StatusDocument extends Enum implements JsonSerializable
{
    public function jsonSerialize(): string
    {
        return $this->value();
    }
}
