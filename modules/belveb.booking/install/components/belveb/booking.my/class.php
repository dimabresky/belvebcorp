<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/belveb.booking/lib/bootstrap.php';

use Belveb\Booking\View\ScreenData;

class BelvebBookingMy extends CBitrixComponent
{
    public function executeComponent(): void
    {
        global $USER;

        if (!is_object($USER) || !$USER->IsAuthorized()) {
            $this->includeComponentTemplate('auth');

            return;
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $this->arResult['STATE'] = (new ScreenData(
            '/local/components/belveb/booking.rooms/templates/.default/images'
        ))->pages()->my($page);
        $this->includeComponentTemplate();
    }
}
