<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Belveb\Booking\View\ScreenData;
use Bitrix\Main\Loader;

class BelvebBookingRooms extends CBitrixComponent
{
    public function executeComponent(): void
    {
        if (!Loader::includeModule('belveb.booking')) {
            ShowError('Модуль belveb.booking не установлен');

            return;
        }

        $state = ScreenData::open()->pages()->rooms();
        $this->arResult = $state;
        $this->arResult['FILTER'] = $state['filter'];
        $this->arResult['FILTER_OPTIONS'] = $state['filterOptions'];
        $this->arResult['ROOMS'] = $state['rooms'];
        $this->arResult['URLS'] = $state['urls'];
        $this->includeComponentTemplate();
    }
}
