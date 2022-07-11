<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones;

use DOMDocument;
use PhpCfdi\SatEstadoRetenciones\Contracts\ScraperInterface;
use PhpCfdi\SatEstadoRetenciones\Internal\RetentionReader10;
use PhpCfdi\SatEstadoRetenciones\Internal\RetentionReaderInterface;

final class Service
{
    private ScraperInterface $scraper;

    public function __construct(ScraperInterface $scraper = null)
    {
        $this->scraper = $scraper ?? new Scraper();
    }

    public function getScraper(): ScraperInterface
    {
        return $this->scraper;
    }

    /**
     * Query parameters and obtain the result
     *
     * @param Parameters $parameters
     * @return Result
     * @throws Exceptions\RetentionNotFoundException if retention document was not found
     * @throws Exceptions\HttpClientException if unable to retrieve contents because HTTP error
     */
    public function query(Parameters $parameters): Result
    {
        return $this->scraper->obtainStatus($parameters);
    }

    /**
     * Query parameters and obtain the result, if not found returns NULL
     *
     * @param Parameters $parameters
     * @return Result|null
     * @throws Exceptions\HttpClientException if unable to retrieve contents because HTTP error
     */
    public function queryOrNull(Parameters $parameters): ?Result
    {
        try {
            return $this->scraper->obtainStatus($parameters);
        } catch (Exceptions\RetentionNotFoundException $exception) {
            return null;
        }
    }

    /**
     * Makes a parameters object for the given XML
     *
     * @param string $xml
     * @return Parameters
     */
    public function makeParametersFromXml(string $xml): Parameters
    {
        $document = new DOMDocument();
        $document->loadXML($xml);
        return $this->makeParametersFromDocument($document);
    }

    /**
     * Makes a parameters object for the given DOM Document
     *
     * @param DOMDocument $document
     * @return Parameters
     */
    public function makeParametersFromDocument(DOMDocument $document): Parameters
    {
        /** @var RetentionReaderInterface[] $readers */
        $readers = [
            new RetentionReader10($document),
        ];

        $reader = null;
        foreach ($readers as $test) {
            if ($test->matchDocument()) {
                $reader = $test;
                break;
            }
        }
        if (null === $reader) {
            return new Parameters('', '', '');
        }

        return new Parameters(
            $reader->obtainUUID(),
            $reader->obtainRfcIssuer(),
            $reader->obtainRfcReceiver(),
        );
    }
}
