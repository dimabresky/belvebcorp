<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var CBitrixComponentTemplate $this */
/** @var array $arResult */

$img = static function (string $file) use ($templateFolder): string {
    return htmlspecialcharsbx($templateFolder . '/images/' . $file);
};

$filter = $arResult['FILTER'];
?>
<div class="flex w-full flex-col">
    <h1 class="text-[40px] font-bold leading-[43px] text-foreground"><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_TITLE')) ?></h1>

    <nav class="mt-[15px] flex items-center gap-[10px] text-[20px] leading-[22.5px] text-muted-soft" aria-label="<?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_BREADCRUMB')) ?>">
        <a class="inline-flex items-center gap-[10px] text-muted-soft" href="/">
            <img src="<?= $img('icon-home.svg') ?>" width="16.5" height="16.7" alt="">
            <span><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_HOME')) ?></span>
        </a>
        <span class="text-[15px] leading-[22.5px]" aria-hidden="true">/</span>
        <span><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_TITLE')) ?></span>
    </nav>

    <div class="mt-[31px] inline-flex items-end gap-[46px] border-b border-border" role="tablist">
        <button
            type="button"
            class="-mb-px min-w-[302px] cursor-pointer border-0 border-b-[3px] border-primary bg-transparent pb-0 text-center text-[30px] font-bold leading-[43px] text-foreground"
            role="tab"
            aria-selected="true"
        ><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_TAB_ROOMS')) ?></button>
        <button
            type="button"
            class="cursor-pointer border-0 bg-transparent pb-[3px] text-[30px] font-bold leading-[43px] text-muted"
            role="tab"
            aria-selected="false"
        ><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_TAB_MINE')) ?></button>
    </div>

    <div class="card mt-[43px] flex h-20 items-center justify-between border-border px-5 shadow-[0_4px_9px_rgba(154,154,154,0.12)]">
        <div class="flex items-center gap-5">
            <div class="flex items-center gap-[25px]">
                <div class="flex w-[260px] items-center gap-[10px] rounded-[15px] bg-canvas px-2 py-[11px]">
                    <img src="<?= $img('icon-calendar.svg') ?>" width="25" height="25" alt="">
                    <span class="flex flex-col gap-[5px]">
                        <span class="text-[13px] font-medium leading-[14px] text-muted"><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_FILTER_DATE')) ?></span>
                        <span class="text-[15px] font-medium leading-[15px] text-foreground"><?= htmlspecialcharsbx($filter['DATE']) ?></span>
                    </span>
                </div>
                <span class="h-[34px] w-0.5 rounded-[2px] bg-[#d2d2d2]" aria-hidden="true"></span>
            </div>
            <div class="flex w-[260px] items-center justify-between rounded-[15px] bg-canvas px-2 py-[11px]">
                <span class="inline-flex items-center gap-[10px]">
                    <img src="<?= $img('icon-time.svg') ?>" width="22.3333" height="22.3333" alt="">
                    <span class="flex flex-col gap-[5px]">
                        <span class="text-[13px] font-medium leading-[14px] text-muted"><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_FILTER_TIME')) ?></span>
                        <span class="text-[15px] font-medium leading-[15px] text-foreground"><?= htmlspecialcharsbx($filter['TIME']) ?></span>
                    </span>
                </span>
                <img src="<?= $img('icon-chevron-sm.svg') ?>" width="20" height="20" alt="">
            </div>
            <span class="h-[34px] w-0.5 rounded-[2px] bg-[#d2d2d2]" aria-hidden="true"></span>
            <div class="flex w-[202px] items-center justify-between rounded-[15px] bg-canvas px-2 py-[11px]">
                <span class="inline-flex items-center gap-[10px]">
                    <img src="<?= $img('icon-people.svg') ?>" width="24.1651" height="22.8333" alt="">
                    <span class="flex flex-col gap-[5px]">
                        <span class="text-[13px] font-medium leading-[14px] text-muted"><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_FILTER_PARTICIPANTS')) ?></span>
                        <span class="text-[15px] font-medium leading-[15px] text-foreground"><?= htmlspecialcharsbx($filter['PARTICIPANTS']) ?></span>
                    </span>
                </span>
                <img src="<?= $img('icon-chevron-sm.svg') ?>" width="20" height="20" alt="">
            </div>
        </div>
        <button
            type="button"
            class="flex h-14 w-[85px] cursor-pointer items-center justify-center rounded-lg border-0 bg-[#4470e2]"
            aria-label="<?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_SEARCH')) ?>"
        >
            <img src="<?= $img('icon-search.svg') ?>" width="25" height="25" alt="">
        </button>
    </div>

    <div class="mt-[27px] flex items-center justify-between gap-5">
        <div class="flex items-center gap-5">
            <button type="button" class="flex w-[155px] cursor-pointer items-center justify-between rounded-[20px] border border-border bg-white px-[10px] py-4 text-[15px] font-medium leading-[22px] text-[#211c1c]">
                <span><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_FLOOR')) ?></span>
                <img src="<?= $img('icon-chevron.svg') ?>" width="24" height="24" alt="">
            </button>
            <button type="button" class="flex cursor-pointer items-center gap-[65px] rounded-[20px] border border-border bg-white px-[10px] py-4 text-[15px] font-medium leading-[22px] text-[#211c1c]">
                <span><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_TYPE')) ?></span>
                <img src="<?= $img('icon-chevron.svg') ?>" width="24" height="24" alt="">
            </button>
        </div>
        <button type="button" class="cursor-pointer border-0 bg-transparent text-[15px] font-medium leading-[22px] text-primary">
            <?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_RESET')) ?>
        </button>
    </div>

    <h2 class="mt-12 text-[30px] font-bold leading-[43px] text-foreground"><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_AVAILABLE')) ?></h2>

    <ul class="mt-[31px] flex flex-col gap-[25px]">
        <?php foreach ($arResult['ROOMS'] as $room) { ?>
            <li class="card flex items-center gap-[45px] border-2 border-border py-[26px] pr-[21px] pl-[31px]">
                <img
                    class="h-[290px] w-[403px] shrink-0 rounded-[16px] object-cover"
                    src="<?= $img($room['IMAGE']) ?>"
                    width="403"
                    height="290"
                    alt="<?= htmlspecialcharsbx($room['TITLE']) ?>"
                >
                <div class="flex min-w-0 flex-1 flex-col gap-6">
                    <div class="flex flex-col gap-px">
                        <p class="text-[25px] font-medium leading-[43px] text-muted"><?= htmlspecialcharsbx($room['CODE']) ?></p>
                        <h3 class="text-[30px] font-bold leading-[43px] text-foreground"><?= htmlspecialcharsbx($room['TITLE']) ?></h3>
                    </div>
                    <div class="flex flex-col gap-[25px]">
                        <ul class="flex max-w-[555px] flex-wrap gap-x-[10px] gap-y-[15px]">
                            <?php foreach ($room['AMENITIES'] as $amenity) { ?>
                                <li class="inline-flex h-[35px] items-center gap-[10px] rounded-[15px] bg-tint px-[22px] text-[15px] font-medium leading-[22px] text-heading">
                                    <img
                                        src="<?= $img($amenity['ICON']) ?>"
                                        width="<?= htmlspecialcharsbx($amenity['WIDTH']) ?>"
                                        height="<?= htmlspecialcharsbx($amenity['HEIGHT']) ?>"
                                        alt=""
                                    >
                                    <span><?= htmlspecialcharsbx($amenity['LABEL']) ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="flex flex-col gap-6">
                            <span class="h-px w-full bg-border"></span>
                            <div class="flex items-center justify-between gap-4">
                                <span class="inline-flex items-center gap-[7px] text-[15px] font-medium leading-[15px] text-heading">
                                    <img src="<?= $img('icon-person.svg') ?>" width="19.6967" height="22.3333" alt="">
                                    <?= htmlspecialcharsbx($room['CAPACITY']) ?>
                                </span>
                                <span class="inline-flex items-center gap-[7px] text-[15px] font-medium leading-[15px] text-heading">
                                    <img src="<?= $img('icon-building.svg') ?>" width="25" height="25" alt="">
                                    <?= htmlspecialcharsbx($room['FLOOR']) ?>
                                </span>
                                <button type="button" class="btn btn-primary w-[212px]"><?= htmlspecialcharsbx(GetMessage('BELVEB_BOOKING_ROOMS_BOOK')) ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>
