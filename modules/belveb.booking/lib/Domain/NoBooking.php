<?php

namespace Belveb\Booking\Domain;

final class NoBooking
{
    public function known(): bool
    {
        return false;
    }

    public function id(): int
    {
        return 0;
    }
}
