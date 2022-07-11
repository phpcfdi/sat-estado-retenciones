<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Internal;

/** @internal */
interface RetentionReaderInterface
{
    public function matchDocument(): bool;

    public function obtainUUID(): string;

    public function obtainRfcIssuer(): string;

    public function obtainRfcReceiver(): string;
}
