<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit;

use PhpCfdi\SatEstadoRetenciones\Contracts\HttpClientInterface;
use PhpCfdi\SatEstadoRetenciones\Exceptions\HttpClientException;
use PhpCfdi\SatEstadoRetenciones\Exceptions\RetentionNotFoundException;
use PhpCfdi\SatEstadoRetenciones\HttpClients\PhpStreamContextHttpClient;
use PhpCfdi\SatEstadoRetenciones\Parameters;
use PhpCfdi\SatEstadoRetenciones\Scraper;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;

final class ScraperTest extends TestCase
{
    public function testObtainStatusUsingFakeHttpClientExistent(): void
    {
        $fakeHttpClient = new class () implements HttpClientInterface {
            public function getContents(string $url): string
            {
                return TestCase::fileContents('result.html');
            }
        };

        $parameters = new Parameters(
            '48C4CE37-E218-4AAE-97BE-20634A36C628', // UUID
            'DCM991109KR2', // RFC Emisor
            'SAZD861013FU2', // RFC Receptor
        );

        $scraper = new Scraper($fakeHttpClient);
        $result = $scraper->obtainStatus($parameters);

        $this->assertTrue($result->statusDocument->isActive());
    }

    public function testObtainStatusUsingFakeHttpClientNotFound(): void
    {
        $fakeHttpClient = new class () implements HttpClientInterface {
            public function getContents(string $url): string
            {
                return TestCase::fileContents('result-not-found.html');
            }
        };

        $parameters = new Parameters(
            '48C4CE37-E218-4AAE-97BE-20634A36C628', // UUID
            'DCM991109KR2', // RFC Emisor
            'SAZD861013FU2', // RFC Receptor
        );

        $scraper = new Scraper($fakeHttpClient);

        $this->expectException(RetentionNotFoundException::class);
        $scraper->obtainStatus($parameters);
    }

    public function testObtainStatusUsingFakeHttpClientError(): void
    {
        $fakeHttpClient = new class () implements HttpClientInterface {
            public function getContents(string $url): string
            {
                throw new HttpClientException($url, 404, '');
            }
        };

        $parameters = new Parameters(
            '48C4CE37-E218-4AAE-97BE-20634A36C628', // UUID
            'DCM991109KR2', // RFC Emisor
            'SAZD861013FU2', // RFC Receptor
        );

        $scraper = new Scraper($fakeHttpClient);

        $this->expectException(HttpClientException::class);
        $scraper->obtainStatus($parameters);
    }

    public function testPropertyDefaultHttpClientInterface(): void
    {
        $scraper = new Scraper();
        $this->assertInstanceOf(PhpStreamContextHttpClient::class, $scraper->httpClient);
    }

    public function testPropertyHttpClientInterface(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $scraper = new Scraper($httpClient);
        $this->assertSame($httpClient, $scraper->httpClient);
    }

    public function testMakeUrlHasValuesOnQueryString(): void
    {
        $parameters = new Parameters('12345678-1234-1234-1234-123456789012', 'AAA010101AAA', 'XXXX991231XX0');
        $scraper = new Scraper();
        $url = $scraper->makeUrl($parameters);
        $queryString = (string) parse_url($url, PHP_URL_QUERY);
        parse_str($queryString, $queryValues);

        $expectedValues = array_filter([
            'folio' => '12345678-1234-1234-1234-123456789012',
            'rfcEmisor' => 'AAA010101AAA',
            'rfcReceptor' => 'XXXX991231XX0',
            '_' => $queryValues['_'] ?? null,
        ]);

        $this->assertSame($expectedValues, $queryValues);
        $this->assertTrue(is_numeric($queryValues['_'] ?? null), 'url does not contains "_" on query string');
    }

    public function testObtainMillisecondsParameter(): void
    {
        $microtime = idate('U', strtotime('2021-01-13T14:15:16 -06:00')) + 0.123123;
        $expected = 1610568916123;

        $scraper = new Scraper();
        $this->assertSame($expected, $scraper->obtainMillisecondsParameter($microtime));
        $this->assertGreaterThanOrEqual(
            $scraper->obtainMillisecondsParameter((float) time()),
            $scraper->obtainMillisecondsParameter()
        );
    }
}
