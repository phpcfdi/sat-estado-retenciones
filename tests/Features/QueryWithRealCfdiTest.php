<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Features;

use PhpCfdi\SatEstadoRetenciones\Service;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;

class QueryWithRealCfdiTest extends TestCase
{
    public function testQueryWithRetentions10MexicanReal(): void
    {
        $contents = $this->fileContents('ret10-mexican-real.xml');
        $service = new Service();
        $parameters = $service->makeParametersFromXml($contents);
        $result = $service->query($parameters);

        $this->assertTrue($result->getStatusDocument()->isActive());
        $this->assertSame($parameters->getIssuerRfc(), $result->getIssuerRfc());
        $this->assertSame($parameters->getReceiverRfc(), $result->getReceiverRfc());
        $this->assertSame($parameters->getUuid(), $result->getUuid());
    }

    public function testQueryWithRetentions20MexicanReal(): void
    {
        $contents = $this->fileContents('ret20-mexican-real.xml');
        $service = new Service();
        $parameters = $service->makeParametersFromXml($contents);
        $result = $service->query($parameters);

        $this->assertTrue($result->getStatusDocument()->isActive());
        $this->assertSame($parameters->getIssuerRfc(), $result->getIssuerRfc());
        $this->assertSame($parameters->getReceiverRfc(), $result->getReceiverRfc());
        $this->assertSame($parameters->getUuid(), $result->getUuid());
    }
}
