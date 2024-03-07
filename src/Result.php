<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones;

use JsonSerializable;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\Amount;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusDocument;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusEfos;

final readonly class Result implements JsonSerializable
{
    public StatusDocument $statusDocument;

    public Amount $totalAmount;

    public StatusEfos $statusEfos;

    public function __construct(
        public string $issuerRfc,
        public string $issuerName,
        public string $receiverRfc,
        public string $receiverName,
        public string $uuid,
        public string $expedition,
        public string $certification,
        public string $pacRfc,
        public string $total,
        public string $state,
        public string $efos
    ) {
        $this->statusDocument = StatusDocument::fromValue($this->state);
        $this->statusEfos = StatusEfos::fromValue($this->efos);
        $this->totalAmount = Amount::newFromString($this->total);
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
