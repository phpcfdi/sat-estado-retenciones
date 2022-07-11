<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Internal;

use DOMDocument;
use DOMNodeList;
use DOMXPath;

/** @internal */
final class RetentionReader10 implements RetentionReaderInterface
{
    private const NS_RETENTION = 'http://www.sat.gob.mx/esquemas/retencionpago/1';

    private const NS_TIMBRE_FISCAL_DIGITAL = 'http://www.sat.gob.mx/TimbreFiscalDigital';

    private DOMXPath $xpath;

    public function __construct(DOMDocument $document)
    {
        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('r', self::NS_RETENTION);
        $xpath->registerNamespace('t', self::NS_TIMBRE_FISCAL_DIGITAL);
        $this->xpath = $xpath;
    }

    public function matchDocument(): bool
    {
        return ('1.0' === $this->obtainFirstAttributeValue('/r:Retenciones/@Version'));
    }

    public function obtainUUID(): string
    {
        return $this->obtainFirstAttributeValue('/r:Retenciones/r:Complemento/t:TimbreFiscalDigital/@UUID');
    }

    public function obtainRfcIssuer(): string
    {
        return $this->obtainFirstAttributeValue('/r:Retenciones/r:Emisor/@RFCEmisor');
    }

    public function obtainRfcReceiver(): string
    {
        return $this->obtainFirstAttributeValue('/r:Retenciones/r:Receptor/r:Nacional/@RFCRecep');
    }

    private function obtainFirstAttributeValue(string $xquery): string
    {
        $attributes = $this->xpath->query($xquery, null, false) ?: new DOMNodeList();
        $attribute = $attributes->item(0);
        if (null === $attribute) {
            return '';
        }
        return $attribute->nodeValue ?? '';
    }
}
