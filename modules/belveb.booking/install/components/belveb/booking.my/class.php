<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Belveb\Booking\View\ScreenData;
use Bitrix\Main\Loader;

class BelvebBookingMy extends CBitrixComponent
{
    public function executeComponent(): void
    {
        if (!Loader::includeModule('belveb.booking')) {
            ShowError('Модуль belveb.booking не установлен');

            return;
        }

        global $USER;

        if (!is_object($USER) || !$USER->IsAuthorized()) {
            $this->includeComponentTemplate('auth');

            return;
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $state = ScreenData::open()->pages()->my($page);
        $this->arResult = $state;
        $this->arResult['ACTIVE'] = $state['activeBookings'];
        $this->arResult['PREVIOUS'] = $state['previousPage'];
        $this->arResult['NAV'] = $state['nav'];
        $this->includeComponentTemplate();
    }
}
