<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var CBitrixComponent $component */
/** @var array $arResult */
/** @global CMain $APPLICATION */

$APPLICATION->IncludeComponent(
    'belveb:booking.room',
    '',
    [
        'ROOM_CODE' => (string) ($arResult['ROOM_CODE'] ?? ''),
    ],
    $component
);
