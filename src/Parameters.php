<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones;

use JsonSerializable;

final readonly class Parameters implements JsonSerializable
{
    public function __construct(
        public string $uuid,
        public string $issuerRfc,
        public string $receiverRfc,
    ) {
    }

    public static function createEmpty(): self
    {
        return new self('', '', '');
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
