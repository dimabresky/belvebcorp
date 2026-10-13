<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @global CMain $APPLICATION */

$APPLICATION->IncludeComponent('bitrix:system.auth.form', '', [
    'REGISTER_URL' => '',
    'FORGOT_PASSWORD_URL' => '',
    'PROFILE_URL' => '',
    'SHOW_ERRORS' => 'Y',
], false);
