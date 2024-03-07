<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Exceptions;

use PhpCfdi\SatEstadoRetenciones\Parameters;
use RuntimeException;
use Throwable;

final class RetentionNotFoundException extends RuntimeException implements SatEstadoRetencionesException
{
    public function __construct(
        private readonly Parameters $parameters,
        Throwable $previous = null,
    ) {
        $message = sprintf(
            'CFDI Retention %s (issuer: %s, receiver: %s) was not found',
            $parameters->uuid,
            $parameters->issuerRfc,
            $parameters->receiverRfc ?: '<empty>',
        );
        parent::__construct($message, 0, $previous);
    }

    public function getParameters(): Parameters
    {
        return $this->parameters;
    }
}
