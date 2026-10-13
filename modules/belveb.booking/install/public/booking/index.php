<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

/** @global CMain $APPLICATION */
$APPLICATION->SetTitle('Бронирование');

$APPLICATION->IncludeComponent(
    'belveb:booking',
    '',
    [
        'SEF_FOLDER' => '/booking/',
    ],
    false
);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
