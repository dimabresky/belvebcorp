<?php

namespace Belveb\Booking\Domain;

final class ClearRange
{
    public function blocked(): bool
    {
        return false;
    }

    /**
     * @return array{blocked: bool}
     */
    public function view(): array
    {
        return ['blocked' => false];
    }
}
