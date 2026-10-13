<?php

namespace Belveb\Booking\Domain;

interface WorkCalendar
{
    public function isWorkDay(CalendarDate $date): bool;
}
