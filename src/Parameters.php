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

    /** @return array{uuid: string, issuerRfc: string, receiverRfc: string} */
    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid,
            'issuerRfc' => $this->issuerRfc,
            'receiverRfc' => $this->receiverRfc,
        ];
    }
}
