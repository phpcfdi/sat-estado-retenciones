<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones;

use JsonSerializable;

class Parameters implements JsonSerializable
{
    public function __construct(private string $uuid, private string $issuerRfc, private string $receiverRfc)
    {
    }

    public static function createEmpty(): self
    {
        return new self('', '', '');
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getIssuerRfc(): string
    {
        return $this->issuerRfc;
    }

    public function getReceiverRfc(): string
    {
        return $this->receiverRfc;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
