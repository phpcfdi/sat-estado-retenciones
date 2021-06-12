<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit\Exceptions;

use PhpCfdi\SatEstadoRetenciones\Exceptions\HttpClientException;
use PhpCfdi\SatEstadoRetenciones\Exceptions\SatEstadoRetencionesException;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;
use RuntimeException;

final class HttpClientExceptionTest extends TestCase
{
    public function testExceptionTypes(): void
    {
        $exception = new HttpClientException('', 200, '');
        $this->assertInstanceOf(SatEstadoRetencionesException::class, $exception);
        $this->assertInstanceOf(RuntimeException::class, $exception);
    }

    public function testExceptionProperties(): void
    {
        $url = 'https://example.com';
        $statusCode = 404;
        $body = 'body';
        $previous = new RuntimeException();
        $exception = new HttpClientException($url, $statusCode, $body, $previous);

        $this->assertSame($url, $exception->getUrl());
        $this->assertSame($statusCode, $exception->getStatusCode());
        $this->assertSame($body, $exception->getBody());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
