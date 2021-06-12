<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Internal;

use PhpCfdi\SatEstadoRetenciones\Result;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusDocument;
use Symfony\Component\DomCrawler\Crawler;

/** @internal */
class ResultConverter
{
    public function convertHtml(string $html): Result
    {
        $crawler = new Crawler($html);
        return $this->convertCrawler($crawler);
    }

    public function convertCrawler(Crawler $crawler): Result
    {
        $labels = $crawler->filter('#tbl_resultado th')->each(
            function (Crawler $th): string {
                return $th->text();
            }
        );
        $values = $crawler->filter('#tbl_resultado td')->each(
            function (Crawler $td): string {
                return $td->text();
            }
        );

        $dataValues = array_combine($labels, $values) ?: [];
        $dataValues['EFOS'] = (string) $crawler->filter('#efosEstatus')->attr('value');

        return $this->createResultFromValues($dataValues);
    }

    /**
     * @param array<string, string> $values
     * @return Result
     */
    public function createResultFromValues(array $values): Result
    {
        return new Result(
            $values['RFC del Emisor'] ?? '',
            $values['Nombre o Razón Social del Emisor'] ?? '',
            $values['RFC del Receptor'] ?? '',
            $values['Nombre o Razón Social del Receptor'] ?? '',
            $values['Folio Fiscal'] ?? '',
            $values['Fecha de Expedición'] ?? '',
            $values['Fecha Certificación SAT'] ?? '',
            $values['PAC que Certificó'] ?? '',
            $values['Total del CFDI Retención'] ?? '',
            $values['Estado CFDI Retención'] ?? '',
            $values['EFOS'] ?? '',
        );
    }

    public function createStatusDocumentFromValue(string $value): StatusDocument
    {
        if ('Vigente' === $value) {
            return StatusDocument::active();
        }
        if ('Cancelado' === $value) {
            return StatusDocument::cancelled();
        }
        return StatusDocument::unknown();
    }
}
