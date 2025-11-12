<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Exceptions;

use RuntimeException;
use Throwable;

final class HttpClientException extends RuntimeException implements SatEstadoRetencionesException
{
    public function __construct(
        private readonly string $url,
        private readonly int $statusCode,
        private readonly string $body,
        ?Throwable $previous = null,
    ) {
        parent::__construct(sprintf('Unable to connect to %s, status code %d', $url, $statusCode), 0, $previous);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
