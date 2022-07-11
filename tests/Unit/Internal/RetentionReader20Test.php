<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit\Internal;

use DOMDocument;
use PhpCfdi\SatEstadoRetenciones\Internal\RetentionReader20;
use PhpCfdi\SatEstadoRetenciones\Internal\RetentionReaderInterface;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;

final class RetentionReader20Test extends TestCase
{
    public function testRetentionReaderDefinitionTest(): void
    {
        $document = new DOMDocument();
        $reader = new RetentionReader20($document);
        $this->assertInstanceOf(RetentionReaderInterface::class, $reader);
    }

    public function testReadCfdiRetentionMexican(): void
    {
        $document = new DOMDocument();
        $document->load($this->filePath('ret20-mexican-fake.xml'));
        $reader = new RetentionReader20($document);

        $this->assertSame('4E3DD8EA-5220-8C42-85A8-E37F9D7502F8', $reader->obtainUUID());
        $this->assertSame('AAA010101AAA', $reader->obtainRfcIssuer());
        $this->assertSame('SUL010720JN8', $reader->obtainRfcReceiver());
    }

    public function testReadCfdiRetentionForeign(): void
    {
        $document = new DOMDocument();
        $document->load($this->filePath('ret20-foreign-fake.xml'));
        $reader = new RetentionReader20($document);

        $this->assertSame('4E3DD8EA-5220-8C42-85A8-E37F9D7502F8', $reader->obtainUUID());
        $this->assertSame('AAA010101AAA', $reader->obtainRfcIssuer());
        $this->assertSame('', $reader->obtainRfcReceiver());
    }
}
