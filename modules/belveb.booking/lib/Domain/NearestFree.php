<?php

namespace Belveb\Booking\Domain;

final class NearestFree
{
    public function __construct(private TimeRange $range)
    {
    }

    public function found(): bool
    {
        return true;
    }

    public function range(): TimeRange
    {
        return $this->range;
    }
}
