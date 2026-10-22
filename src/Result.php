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
        public string $efos,
    ) {
        $this->statusDocument = StatusDocument::fromValue($this->state);
        $this->statusEfos = StatusEfos::fromValue($this->efos);
        $this->totalAmount = Amount::newFromString($this->total);
    }

    /**
     * @return array{
     *     issuerRfc: string,
     *     issuerName: string,
     *     receiverRfc: string,
     *     receiverName: string,
     *     uuid: string,
     *     expedition: string,
     *     certification: string,
     *     pacRfc: string,
     *     total: string,
     *     state: string,
     *     efos: string,
     *     statusDocument: StatusDocument,
     *     statusEfos: StatusEfos,
     *     totalAmount: Amount,
     *  }
     */
    public function jsonSerialize(): array
    {
        return [
            'issuerRfc' => $this->issuerRfc,
            'issuerName' => $this->issuerName,
            'receiverRfc' => $this->receiverRfc,
            'receiverName' => $this->receiverName,
            'uuid' => $this->uuid,
            'expedition' => $this->expedition,
            'certification' => $this->certification,
            'pacRfc' => $this->pacRfc,
            'total' => $this->total,
            'state' => $this->state,
            'efos' => $this->efos,
            'statusDocument' => $this->statusDocument,
            'statusEfos' => $this->statusEfos,
            'totalAmount' => $this->totalAmount,
        ];
    }
}
