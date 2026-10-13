<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/belveb.booking/lib/bootstrap.php';

use Belveb\Booking\View\ScreenData;

class BelvebBookingRooms extends CBitrixComponent
{
    public function executeComponent(): void
    {
        $this->arResult['STATE'] = $this->screen()->pages()->rooms();
        $this->includeComponentTemplate();
    }

    private function screen(): ScreenData
    {
        return new ScreenData('/local/components/belveb/booking.rooms/templates/.default/images');
    }
}
