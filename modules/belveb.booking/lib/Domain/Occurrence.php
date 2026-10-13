<?php

namespace Belveb\Booking\Domain;

final class Occurrence
{
    public function __construct(
        private CalendarDate $date,
        private TimeRange $range,
    ) {
    }

    public function date(): CalendarDate
    {
        return $this->date;
    }

    public function range(): TimeRange
    {
        return $this->range;
    }
}
