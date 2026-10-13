<?php

namespace Belveb\Booking\Domain;

final class SystemClock implements Clock
{
    public function date(): CalendarDate
    {
        return new CalendarDate((new \DateTimeImmutable())->format('d.m.Y'));
    }

    public function time(): MinuteOfDay
    {
        return new MinuteOfDay((new \DateTimeImmutable())->format('H:i'));
    }
}
