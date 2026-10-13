<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/belveb.booking/lib/bootstrap.php';

use Belveb\Booking\View\ScreenData;

class BelvebBookingRoom extends CBitrixComponent
{
    public function executeComponent(): void
    {
        $code = (string) ($this->arParams['ROOM_CODE'] ?? '');
        $state = (new ScreenData(
            '/local/components/belveb/booking.rooms/templates/.default/images'
        ))->pages()->room($code);

        if (!$state['found']) {
            if (!defined('ERROR_404')) {
                define('ERROR_404', 'Y');
            }
            \CHTTP::SetStatus('404 Not Found');
        }

        $this->arResult['STATE'] = $state;
        $this->includeComponentTemplate();
    }
}
