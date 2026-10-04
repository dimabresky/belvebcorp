<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @global CMain $APPLICATION */

$img = static function (string $file): string {
    return htmlspecialcharsbx(SITE_TEMPLATE_PATH . '/images/' . $file);
};
?>
    </main>
</div>
<footer class="site-footer">
    <div class="site-footer-main">
        <div>
            <span class="logo-frame h-[85px] w-[125px]">
                <img src="<?= $img('logo-footer.png') ?>" alt="БелВЭБ">
            </span>
            <p class="mt-[31px] text-[15px] leading-[12px] tracking-[-0.18px]">ОАО «Банк БелВЭБ»</p>
            <p class="mt-[17px] text-[15px] leading-[12px] tracking-[-0.18px]">220004, г. Минск, пр-т Победителей, 29</p>
        </div>
        <div class="pt-[41px]">
            <p class="font-support text-[20px] leading-normal font-semibold">Поддержка 24/7</p>
            <p class="mt-[9px] flex items-center gap-[9px]">
                <img src="<?= $img('icon-phone.svg') ?>" width="16" height="16" alt="">
                <a class="font-support text-[13px] leading-[30px] whitespace-nowrap" href="tel:+375172156115">+ 375 (17) 215-61-15</a>
            </p>
            <p class="flex items-center gap-[10px]">
                <img src="<?= $img('icon-phone.svg') ?>" width="16" height="16" alt="">
                <span class="font-support text-[13px] leading-[30px] whitespace-nowrap">205 - А1, МТС, Life :)</span>
            </p>
        </div>
    </div>
    <div class="site-footer-bar">
        <span>© «БелВЭБ», 2026</span>
        <a href="#">Карта сайта</a>
        <a href="#">Темы</a>
        <a href="#">Политика обработки персональных данных</a>
    </div>
</footer>
</body>
</html>
