<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Exceptions;

use RuntimeException;
use Throwable;

final class HttpClientException extends RuntimeException implements SatEstadoRetencionesException
{
    private string $url;

    private int $statusCode;

    public function __construct(string $url, int $statusCode, private string $body, Throwable $previous = null)
    {
        parent::__construct(sprintf('Unable to connect to %s, status code %d', $url, $statusCode), 0, $previous);
        $this->url = $url;
        $this->statusCode = $statusCode;
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
