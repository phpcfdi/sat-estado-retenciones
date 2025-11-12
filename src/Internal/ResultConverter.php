<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Internal;

use PhpCfdi\SatEstadoRetenciones\Result;
use Symfony\Component\DomCrawler\Crawler;

/** @internal */
final readonly class ResultConverter
{
    public function convertHtml(string $html): Result
    {
        $crawler = new Crawler($html);
        return $this->convertCrawler($crawler);
    }

    public function convertCrawler(Crawler $crawler): Result
    {
        /** @phpstan-var string[] $labels */
        $labels = $crawler->filter('#tbl_resultado th')->each(
            fn (Crawler $th): string => $th->text()
        );
        /** @phpstan-var string[] $values */
        $values = $crawler->filter('#tbl_resultado td')->each(
            fn (Crawler $td): string => $td->text()
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
            strtoupper($values['Folio Fiscal'] ?? ''),
            $values['Fecha de Expedición'] ?? '',
            $values['Fecha Certificación SAT'] ?? '',
            $values['PAC que Certificó'] ?? '',
            $values['Total del CFDI Retención'] ?? '',
            $values['Estado CFDI Retención'] ?? '',
            $values['EFOS'] ?? '',
        );
    }
}
