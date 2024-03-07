<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones;

use PhpCfdi\SatEstadoRetenciones\Contracts\HttpClientInterface;
use PhpCfdi\SatEstadoRetenciones\Contracts\ScraperInterface;
use PhpCfdi\SatEstadoRetenciones\HttpClients\PhpStreamContextHttpClient;
use PhpCfdi\SatEstadoRetenciones\Internal\ResultConverter;
use Symfony\Component\DomCrawler\Crawler;

final readonly class Scraper implements ScraperInterface
{
    public const SAT_WEBAPP_URL = 'https://prodretencionverificacion.clouda.sat.gob.mx/Home/ConsultaRetencion';

    public function __construct(private HttpClientInterface $httpClient = new PhpStreamContextHttpClient())
    {
    }

    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    public function obtainStatus(Parameters $parameters): Result
    {
        $url = $this->makeUrl($parameters);
        $html = $this->httpClient->getContents($url);
        $crawler = new Crawler($html, $url);
        if ($this->responseIsNotFound($crawler)) {
            throw new Exceptions\RetentionNotFoundException($parameters);
        }
        return (new ResultConverter())->convertCrawler($crawler);
    }

    public function responseIsNotFound(Crawler $crawler): bool
    {
        return ($crawler->filter('.noresultados')->count() > 0);
    }

    public function makeUrl(Parameters $parameters): string
    {
        return self::SAT_WEBAPP_URL . '?' . http_build_query([
            'folio' => $parameters->uuid,
            'rfcEmisor' => $parameters->issuerRfc,
            'rfcReceptor' => $parameters->receiverRfc,
            '_' => $this->obtainMillisecondsParameter(),
        ]);
    }

    public function obtainMillisecondsParameter(float $microtime = null): int
    {
        $microtime ??= microtime(true);
        return intval($microtime * 1000);
    }
}
