<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones;

use DOMDocument;
use PhpCfdi\SatEstadoRetenciones\Contracts\ScraperInterface;
use PhpCfdi\SatEstadoRetenciones\Internal\RetentionReader10;
use PhpCfdi\SatEstadoRetenciones\Internal\RetentionReader20;
use PhpCfdi\SatEstadoRetenciones\Internal\RetentionReaderInterface;

final readonly class Service
{
    public function __construct(public ScraperInterface $scraper = new Scraper())
    {
    }

    /**
     * Query parameters and obtain the result
     *
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
     * @throws Exceptions\HttpClientException if unable to retrieve contents because HTTP error
     */
    public function queryOrNull(Parameters $parameters): Result|null
    {
        try {
            return $this->scraper->obtainStatus($parameters);
        } catch (Exceptions\RetentionNotFoundException) {
            return null;
        }
    }

    /**
     * Makes a parameters object for the given XML
     */
    public function makeParametersFromXml(string $xml): Parameters
    {
        $document = new DOMDocument();
        $document->loadXML($xml);
        return $this->makeParametersFromDocument($document);
    }

    /**
     * Makes a parameters object for the given DOM Document
     */
    public function makeParametersFromDocument(DOMDocument $document): Parameters
    {
        $reader = $this->findRetentionReaderToMakeParametersFromDocument($document);
        if (null === $reader) {
            return Parameters::createEmpty();
        }

        return new Parameters(
            $reader->obtainUUID(),
            $reader->obtainRfcIssuer(),
            $reader->obtainRfcReceiver(),
        );
    }

    private function findRetentionReaderToMakeParametersFromDocument(DOMDocument $document): ?RetentionReaderInterface
    {
        /** @var RetentionReaderInterface[] $readers */
        $readers = [
            new RetentionReader20($document),
            new RetentionReader10($document),
        ];

        foreach ($readers as $test) {
            if ($test->matchDocument()) {
                return $test;
            }
        }

        return null;
    }
}
