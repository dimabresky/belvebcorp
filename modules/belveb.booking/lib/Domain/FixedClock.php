<?php

namespace Belveb\Booking\Domain;

final class FixedClock implements Clock
{
    public function __construct(
        private CalendarDate $date,
        private MinuteOfDay $time,
    ) {
    }

    public function date(): CalendarDate
    {
        return $this->date;
    }

    public function time(): MinuteOfDay
    {
        return $this->time;
    }
}
