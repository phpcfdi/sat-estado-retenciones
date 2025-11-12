<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit\Internal;

use DOMDocument;
use DOMXPath;
use PhpCfdi\SatEstadoRetenciones\Internal\RetentionReaderXpath;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;

final class RetentionReaderXpathTest extends TestCase
{
    public function createRetentionReader(DOMDocument $document): RetentionReaderXpath
    {
        $xpath = new DOMXPath($document);
        // Version 1.5 does not exist, it's a fake
        $xpath->registerNamespace('r', 'http://www.sat.gob.mx/esquemas/retencionpago/1.5');
        $xpath->registerNamespace('t', 'http://www.sat.gob.mx/TimbreFiscalDigital');
        return new class ($xpath) extends RetentionReaderXpath {
            public function __construct(DOMXPath $xpath)
            {
                parent::__construct(
                    $xpath,
                    '/r:Retenciones/@Version',
                    '1.0',
                    '/r:Retenciones/r:Complemento/t:TimbreFiscalDigital/@UUID',
                    '/r:Retenciones/r:Emisor/@RFCEmisor',
                    '/r:Retenciones/r:Receptor/r:Nacional/@RFCRecep',
                );
            }
        };
    }

    public function testReadOtherXml(): void
    {
        $document = new DOMDocument();
        $document->loadXML('<xml />');
        $reader = $this->createRetentionReader($document);

        $this->assertSame('', $reader->obtainUUID());
        $this->assertSame('', $reader->obtainRfcIssuer());
        $this->assertSame('', $reader->obtainRfcReceiver());
    }

    public function testReadEmptyDomDocument(): void
    {
        $document = new DOMDocument();
        $reader = $this->createRetentionReader($document);

        $this->assertSame('', $reader->obtainUUID());
        $this->assertSame('', $reader->obtainRfcIssuer());
        $this->assertSame('', $reader->obtainRfcReceiver());
    }
}
