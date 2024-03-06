<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit\Internal;

use PhpCfdi\SatEstadoRetenciones\Internal\ResultConverter;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;

final class ResultConverterTest extends TestCase
{
    public function testWellKnownConvertHtml(): void
    {
        // The file _files/result.html was obtained directly from SAT at 2021-06-08
        $html = $this->fileContents('result.html');

        $converter = new ResultConverter();
        $result = $converter->convertHtml($html);

        $this->assertSame('DCM991109KR2', $result->issuerRfc);
        $this->assertSame('DEREMATE.COM DE MEXICO S DE RL DE CV', $result->issuerName);
        $this->assertSame('SAZD861013FU2', $result->receiverRfc);
        $this->assertSame('DANIEL SANCHEZ', $result->receiverName);
        $this->assertSame('48C4CE37-E218-4AAE-97BE-20634A36C628', $result->uuid);
        $this->assertSame('2021-02-05T22:44:48', $result->expedition);
        $this->assertSame('2021-02-05T17:36:46', $result->certification);
        $this->assertSame('TLE011122SC2', $result->pacRfc);
        $this->assertSame('$431.03', $result->total);
        $this->assertEqualsWithDelta(431.03, $result->totalAmount->getValue(), 0.001);
        $this->assertSame('Vigente', $result->state);
        $this->assertSame('200', $result->efos);
    }
}
