<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Internal;

use DOMDocument;
use DOMXPath;

/** @internal */
final class RetentionReader20 extends RetentionReaderXpath
{
    public function __construct(DOMDocument $document)
    {
        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('r', 'http://www.sat.gob.mx/esquemas/retencionpago/2');
        $xpath->registerNamespace('t', 'http://www.sat.gob.mx/TimbreFiscalDigital');

        parent::__construct(
            $xpath,
            '/r:Retenciones/@Version',
            '2.0',
            '/r:Retenciones/r:Complemento/t:TimbreFiscalDigital/@UUID',
            '/r:Retenciones/r:Emisor/@RfcE',
            '/r:Retenciones/r:Receptor/r:Nacional/@RfcR'
        );
    }
}
