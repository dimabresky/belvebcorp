<?php

namespace Belveb\Booking\Domain;

final class NoFreeSlot
{
    public function found(): bool
    {
        return false;
    }
}
