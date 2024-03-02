<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Internal;

use DOMNodeList;
use DOMXPath;

/** @internal */
abstract class RetentionReaderXpath implements RetentionReaderInterface
{
    public function __construct(
        private DOMXPath $xpath,
        private string $queryVersion,
        private string $expectedVersion,
        private string $queryUuid,
        private string $queryRfcIssuer,
        private string $queryRfcReceiver
    ) {
    }

    public function matchDocument(): bool
    {
        return ($this->expectedVersion === $this->obtainFirstAttributeValue($this->queryVersion));
    }

    public function obtainUUID(): string
    {
        return $this->obtainFirstAttributeValue($this->queryUuid);
    }

    public function obtainRfcIssuer(): string
    {
        return $this->obtainFirstAttributeValue($this->queryRfcIssuer);
    }

    public function obtainRfcReceiver(): string
    {
        return $this->obtainFirstAttributeValue($this->queryRfcReceiver);
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
