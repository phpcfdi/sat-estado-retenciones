<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\ValueObjects;

use Eclipxe\Enum\Enum;
use JsonSerializable;

/**
 * @method static self included()
 * @method static self excluded()
 * @method static self unknown()
 *
 * @method bool isIncluded()
 * @method bool isExcluded()
 * @method bool isUnknown()
 */
final class StatusEfos extends Enum implements JsonSerializable
{
    public function jsonSerialize(): string
    {
        return $this->value();
    }
}
