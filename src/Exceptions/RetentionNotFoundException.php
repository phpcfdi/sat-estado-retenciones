<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Exceptions;

use PhpCfdi\SatEstadoRetenciones\Parameters;
use RuntimeException;
use Throwable;

final class RetentionNotFoundException extends RuntimeException implements SatEstadoRetencionesException
{
    private Parameters $parameters;

    public function __construct(Parameters $parameters, Throwable $previous = null)
    {
        $message = sprintf(
            'CFDI Retention %s (issuer: %s, receiver: %s) was not found',
            $parameters->getUuid(),
            $parameters->getIssuerRfc(),
            $parameters->getReceiverRfc() ?: '<empty>',
        );
        parent::__construct($message, 0, $previous);
        $this->parameters = $parameters;
    }

    public function getParameters(): Parameters
    {
        return $this->parameters;
    }
}
