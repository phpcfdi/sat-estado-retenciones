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

        $this->assertSame('DCM991109KR2', $result->getIssuerRfc());
        $this->assertSame('DEREMATE.COM DE MEXICO S DE RL DE CV', $result->getIssuerName());
        $this->assertSame('SAZD861013FU2', $result->getReceiverRfc());
        $this->assertSame('DANIEL SANCHEZ', $result->getReceiverName());
        $this->assertSame('48C4CE37-E218-4AAE-97BE-20634A36C628', $result->getUuid());
        $this->assertSame('2021-02-05T22:44:48', $result->getExpedition());
        $this->assertSame('2021-02-05T17:36:46', $result->getCertification());
        $this->assertSame('TLE011122SC2', $result->getPacRfc());
        $this->assertSame('$431.03', $result->getTotal());
        $this->assertEqualsWithDelta(431.03, $result->getTotalAmount()->getValue(), 0.001);
        $this->assertSame('Vigente', $result->getState());
        $this->assertSame('200', $result->getEfos());
    }

    public function testCreateStatusDocumentFromValueActive(): void
    {
        $converter = new ResultConverter();
        $this->assertTrue($converter->createStatusDocumentFromValue('Vigente')->isActive());
    }

    public function testCreateStatusDocumentFromValueCancelled(): void
    {
        $converter = new ResultConverter();
        $this->assertTrue($converter->createStatusDocumentFromValue('Cancelado')->isCancelled());
    }

    /**
     * @testWith [""]
     *           ["Otro valor"]
     *           ["vigente"]
     */
    public function testCreateStatusDocumentFromValueNotFound(string $value): void
    {
        $converter = new ResultConverter();
        $this->assertTrue($converter->createStatusDocumentFromValue($value)->isUnknown());
    }
}
