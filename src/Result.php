<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones;

use JsonSerializable;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\Amount;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusDocument;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusEfos;

class Result implements JsonSerializable
{
    private StatusDocument $statusDocument;

    private Amount $totalAmount;

    private StatusEfos $statusEfos;

    public function __construct(
        private string $issuerRfc,
        private string $issuerName,
        private string $receiverRfc,
        private string $receiverName,
        private string $uuid,
        private string $expedition,
        private string $certification,
        private string $pacRfc,
        private string $total,
        private string $state,
        private string $efos
    ) {
        $this->statusDocument = $this->makeStatusDocument($this->state);
        $this->statusEfos = $this->makeStatusEfos($this->efos);
        $this->totalAmount = Amount::newFromString($this->total);
        $this->uuid = strtoupper($this->uuid);
    }

    private function makeStatusDocument(string $state): StatusDocument
    {
        if ('Vigente' === $state) {
            return StatusDocument::active();
        }
        if ('Cancelado' === $state) {
            return StatusDocument::cancelled();
        }
        return StatusDocument::unknown();
    }

    private function makeStatusEfos(string $efos): StatusEfos
    {
        if ('100' === $efos) {
            return StatusEfos::included();
        }
        if ('200' === $efos) {
            return StatusEfos::excluded();
        }
        return StatusEfos::unknown();
    }

    public function getStatusDocument(): StatusDocument
    {
        return $this->statusDocument;
    }

    public function getStatusEfos(): StatusEfos
    {
        return $this->statusEfos;
    }

    public function getIssuerRfc(): string
    {
        return $this->issuerRfc;
    }

    public function getIssuerName(): string
    {
        return $this->issuerName;
    }

    public function getReceiverRfc(): string
    {
        return $this->receiverRfc;
    }

    public function getReceiverName(): string
    {
        return $this->receiverName;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getExpedition(): string
    {
        return $this->expedition;
    }

    public function getCertification(): string
    {
        return $this->certification;
    }

    public function getPacRfc(): string
    {
        return $this->pacRfc;
    }

    public function getTotal(): string
    {
        return $this->total;
    }

    public function getTotalAmount(): Amount
    {
        return $this->totalAmount;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getEfos(): string
    {
        return $this->efos;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
