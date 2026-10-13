<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle('Бронирование');

$APPLICATION->IncludeComponent(
    'belveb:booking.rooms',
    '',
    [],
    false
);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
