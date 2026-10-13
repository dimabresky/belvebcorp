<?php

namespace Belveb\Booking\Domain;

interface Clock
{
    public function date(): CalendarDate;

    public function time(): MinuteOfDay;
}
