<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/belveb.booking/lib/bootstrap.php';

(new Belveb\Booking\View\AppFrame($arResult['STATE']))->render();
