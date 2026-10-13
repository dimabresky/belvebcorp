<?php

namespace Belveb\Booking\Domain;

final class EveryDayCalendar implements WorkCalendar
{
    public function isWorkDay(CalendarDate $date): bool
    {
        return true;
    }
}
