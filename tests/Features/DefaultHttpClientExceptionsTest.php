<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Features;

use PhpCfdi\SatEstadoRetenciones\Exceptions\HttpClientException;
use PhpCfdi\SatEstadoRetenciones\HttpClients\PhpStreamContextHttpClient;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;

final class DefaultHttpClientExceptionsTest extends TestCase
{
    private function catchExceptionOnGetContents(string $url): HttpClientException
    {
        $client = new PhpStreamContextHttpClient();
        try {
            $client->getContents($url);
        } catch (HttpClientException $exception) {
            return $exception;
        }
        $this->fail(sprintf('Exception %s was not thrown', HttpClientException::class));
    }

    #[WithoutErrorHandler]
    public function testExceptionOnConnectionError(): void
    {
        $url = 'https://non-existent-host.localhost/';
        $exception = $this->catchExceptionOnGetContents($url);
        $this->assertSame(500, $exception->getStatusCode());
        $this->assertStringContainsString($url, $exception->getPrevious()?->getMessage() ?? '');
    }

    #[WithoutErrorHandler]
    public function testExceptionOnUnavailablePath(): void
    {
        $url = 'https://free.mockerapi.com/status/404';
        $exception = $this->catchExceptionOnGetContents($url);
        $this->assertSame(404, $exception->getStatusCode());
        $this->assertStringContainsString($url, $exception->getPrevious()?->getMessage() ?? '');
    }

    #[WithoutErrorHandler]
    public function testExceptionOnServerError(): void
    {
        $url = 'https://free.mockerapi.com/status/504';
        $exception = $this->catchExceptionOnGetContents($url);
        $this->assertSame(504, $exception->getStatusCode());
        $this->assertStringContainsString($url, $exception->getPrevious()?->getMessage() ?? '');
    }
}
