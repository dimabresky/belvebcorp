<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Belveb\Booking\View\ScreenData;
use Bitrix\Main\Loader;

class BelvebBookingRoom extends CBitrixComponent
{
    public function executeComponent(): void
    {
        if (!Loader::includeModule('belveb.booking')) {
            ShowError('Модуль belveb.booking не установлен');

            return;
        }

        $code = (string) ($this->arParams['ROOM_CODE'] ?? '');
        $state = ScreenData::open()->pages()->room($code);
        if (!$state['found']) {
            if (!defined('ERROR_404')) {
                define('ERROR_404', 'Y');
            }
            \CHTTP::SetStatus('404 Not Found');
        }

        $this->arResult = $state;
        $this->arResult['ROOM'] = $state['room'];
        $this->arResult['DAY'] = $state['day'];
        $this->arResult['DAYS'] = $state['days'];
        $this->arResult['TIMELINE'] = $state['timeline'];
        $this->arResult['DAY_BOOKINGS'] = $state['dayBookings'];
        $this->arResult['GRID'] = $state['grid'];
        $this->includeComponentTemplate();
    }
}
