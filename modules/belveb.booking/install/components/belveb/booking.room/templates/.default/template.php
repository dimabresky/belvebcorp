<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */

use Bitrix\Main\UI\Extension;
use Bitrix\Main\Web\Json;

if (empty($arResult['found'])) {
    echo '<p class="text-[15px] text-muted">Переговорная не найдена</p>';

    return;
}

Extension::load('belveb.booking');
?>
<div id="belveb-booking-app"></div>
<script>
BX.ready(function () {
    BX.Belveb.Booking.mount(<?= Json::encode($arResult) ?>);
});
</script>
