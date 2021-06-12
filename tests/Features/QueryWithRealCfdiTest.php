<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Features;

use PhpCfdi\SatEstadoRetenciones\Service;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;

class QueryWithRealCfdiTest extends TestCase
{
    public function testQueryWithRealCfdi(): void
    {
        $contents = $this->fileContents('real-sample.xml');
        $service = new Service();
        $parameters = $service->makeParametersFromXml($contents);
        $result = $service->query($parameters);

        $this->assertTrue($result->getStatusDocument()->isActive());
        $this->assertSame($parameters->getIssuerRfc(), $result->getIssuerRfc());
        $this->assertSame($parameters->getReceiverRfc(), $result->getReceiverRfc());
        $this->assertSame($parameters->getUuid(), $result->getUuid());
    }
}
