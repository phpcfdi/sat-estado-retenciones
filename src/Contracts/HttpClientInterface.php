<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Contracts;

use PhpCfdi\SatEstadoRetenciones\Exceptions\HttpClientException;

interface HttpClientInterface
{
    /**
     * Execute a GET to the url and return the content
     *
     * @param string $url
     * @return string
     * @throws HttpClientException when unable to retrieve contents
     */
    public function getContents(string $url): string;
}
