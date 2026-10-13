<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var CBitrixComponent $component */
/** @global CMain $APPLICATION */

$APPLICATION->IncludeComponent(
    'belveb:booking.my',
    '',
    [],
    $component
);
