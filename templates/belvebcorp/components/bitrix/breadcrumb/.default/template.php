<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */

$home = SITE_TEMPLATE_PATH . '/images/icon-home.svg';
?>
<nav class="mt-[15px] flex flex-wrap items-center gap-[10px] text-[20px] leading-[22.5px] text-muted-soft" aria-label="Хлебные крошки">
    <a class="inline-flex items-center gap-[10px] text-muted-soft" href="/">
        <img src="<?= htmlspecialcharsbx($home) ?>" width="16.5" height="16.7" alt="">
        <span>Главная</span>
    </a>
    <?php foreach ($arResult as $item) {
        if (($item['LINK'] ?? '') === '/' || ($item['TITLE'] ?? '') === 'Главная') {
            continue;
        }
        ?>
        <span class="text-[15px] leading-[22.5px]" aria-hidden="true">/</span>
        <?php if (($item['LINK'] ?? '') !== '') { ?>
            <a class="text-muted-soft" href="<?= htmlspecialcharsbx($item['LINK']) ?>"><?= htmlspecialcharsbx($item['TITLE']) ?></a>
        <?php } else { ?>
            <span><?= htmlspecialcharsbx($item['TITLE']) ?></span>
        <?php } ?>
    <?php } ?>
</nav>
