<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit\Internal;

use DOMDocument;
use PhpCfdi\SatEstadoRetenciones\Internal\RetentionReader;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;

final class RetentionReaderTest extends TestCase
{
    public function testReadCfdiRetention(): void
    {
        $document = new DOMDocument();
        $document->load($this->filePath('real-sample.xml'));
        $reader = new RetentionReader($document);

        $this->assertSame('48C4CE37-E218-4AAE-97BE-20634A36C628', $reader->obtainUUID());
        $this->assertSame('DCM991109KR2', $reader->obtainRfcIssuer());
        $this->assertSame('SAZD861013FU2', $reader->obtainRfcReceiver());
    }

    public function testReadOtherXml(): void
    {
        $document = new DOMDocument();
        $document->loadXML('<xml />');
        $reader = new RetentionReader($document);

        $this->assertSame('', $reader->obtainUUID());
        $this->assertSame('', $reader->obtainRfcIssuer());
        $this->assertSame('', $reader->obtainRfcReceiver());
    }

    public function testReadEmptyDomDocument(): void
    {
        $document = new DOMDocument();
        $reader = new RetentionReader($document);

        $this->assertSame('', $reader->obtainUUID());
        $this->assertSame('', $reader->obtainRfcIssuer());
        $this->assertSame('', $reader->obtainRfcReceiver());
    }
}
