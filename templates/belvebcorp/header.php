<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @global CMain $APPLICATION */

$img = static function (string $file): string {
    return htmlspecialcharsbx(SITE_TEMPLATE_PATH . '/images/' . $file);
};

$logoutUrl = htmlspecialcharsbx($APPLICATION->GetCurPageParam(
    'logout=yes&' . bitrix_sessid_get(),
    ['logout', 'sessid']
));

$resources = [
    'Наименование реурса _1',
    'Наименование реурса _2',
    'Наименование реурса _3',
    'Наименование реурса _4',
];

$actions = [
    [
        'icon' => 'icon-office.svg',
        'width' => '29',
        'height' => '29',
        'label' => 'Забронировать переговорную',
        'labelClass' => 'text-[15px] font-medium leading-7 tracking-[-0.8px] text-[var(--color-heading)]',
    ],
    [
        'icon' => 'icon-help.svg',
        'width' => '24',
        'height' => '24',
        'label' => 'Сообщить в тех.отдел о проблеме',
        'labelClass' => 'w-[153px] text-[15px] font-medium leading-[15px] tracking-[-0.8px] text-[var(--color-heading)]',
    ],
];

$dishes = [
    [
        'title' => 'Борщ с говядиной',
        'text' => 'Классический борщ со сметаной и зеленью',
        'price' => '4.50 BYN',
    ],
    [
        'title' => 'Куриный суп с лапшой',
        'text' => 'Легкий суп с домашней лапшой и овощами',
        'price' => '3.50 BYN',
    ],
    [
        'title' => "Винегрет овощной\nс сельдью",
        'text' => 'картофель,морковь,свекла,огурцы консерв,лук репчатый,заправка для салата',
        'price' => '2.50 BYN',
    ],
];
?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $APPLICATION->ShowTitle(); ?></title>
    <?php $APPLICATION->ShowHead(); ?>
</head>
<body>
<?php $APPLICATION->ShowPanel(); ?>
<div class="site-frame">
<div class="site-canvas">
<header class="site-header">
    <a class="logo-frame h-[55px] w-[81px]" href="/">
        <img src="<?= $img('logo.png') ?>" alt="БелВЭБ">
    </a>
    <nav class="ml-[120px] flex shrink-0 items-center gap-[30px]" aria-label="Основное меню">
        <a class="text-nav whitespace-nowrap text-white" href="#">О Банке</a>
        <a class="text-nav whitespace-nowrap text-white" href="#">Новости</a>
        <a class="text-nav inline-flex items-center gap-[5px] whitespace-nowrap text-white" href="#">
            Подразделения
            <img src="<?= $img('icon-chevron.svg') ?>" width="20" height="20" alt="">
        </a>
        <a class="text-nav inline-flex items-center gap-[5px] whitespace-nowrap text-white" href="#">
            Сотрудники
            <img src="<?= $img('icon-chevron.svg') ?>" width="20" height="20" alt="">
        </a>
        <a class="text-nav whitespace-nowrap text-white" href="#">Ресурсы</a>
    </nav>
    <div class="ml-auto flex shrink-0 items-center gap-5">
        <form class="relative w-[233px]" action="#" method="get" role="search">
            <img
                class="pointer-events-none absolute top-1/2 left-[15px] -translate-y-1/2"
                src="<?= $img('icon-search.svg') ?>"
                width="20.0337"
                height="20"
                alt=""
            >
            <input
                class="input-search w-[233px] pl-[50px]"
                type="search"
                name="q"
                placeholder="Поиск по сайту"
                aria-label="Поиск по сайту"
            >
        </form>
        <button type="button" class="icon-button relative shrink-0" aria-label="Уведомления">
            <img src="<?= $img('icon-bell.svg') ?>" width="13.4998" height="14.8333" alt="">
            <span class="badge absolute top-[-1.5px] left-[26px]">4</span>
        </button>
        <a class="relative inline-block shrink-0" href="#" aria-label="Профиль">
            <span class="avatar-md relative block">
                <img class="avatar-md" src="<?= $img('avatar.png') ?>" width="40" height="40" alt="">
                <img
                    class="absolute top-[-0.5px] left-[-15px] h-[69px] w-[69px] max-w-none object-cover"
                    src="<?= $img('avatar-photo.png') ?>"
                    width="69"
                    height="69"
                    alt=""
                >
            </span>
            <span class="badge-online absolute top-[31.5px] left-[30px]"></span>
        </a>
    </div>
    <a class="btn btn-primary ml-[30px] w-[155px] shrink-0" href="<?= $logoutUrl ?>">Выйти</a>
</header>
<div class="page-shell">
    <aside class="page-sidebar" aria-label="Виджеты">
        <div class="page-sidebar-scale">
        <section class="card flex flex-col gap-8 pt-5 pr-[27px] pb-[18px] pl-[21px]">
            <div class="flex flex-col gap-[13px]">
                <h2 class="text-heading">Популярные ресурсы</h2>
                <ul class="flex flex-col gap-5">
                    <?php foreach ($resources as $resourceName) { ?>
                        <li>
                            <a class="flex items-center gap-[15px]" href="#">
                                <span class="icon-tile">
                                    <img src="<?= $img('icon-link.svg') ?>" width="21.5018" height="21.501" alt="">
                                </span>
                                <span class="text-[15px] font-medium leading-7 tracking-[-0.8px] whitespace-nowrap text-[var(--color-heading)]"><?= htmlspecialcharsbx($resourceName) ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="flex flex-col gap-[7px]">
                <a class="btn h-10 w-full rounded-md border border-accent/60 text-[15px] font-medium tracking-[-0.8px] text-[var(--color-heading)]" href="#">+ Добавить ресурс</a>
                <a class="btn h-10 w-full rounded-md border border-accent/60 bg-accent text-[15px] font-medium tracking-[-0.8px] text-white" href="#">Все ресурсы</a>
            </div>
        </section>
        <section class="card px-[21px] pt-6 pb-[45px]">
            <h2 class="text-heading">Быстрые действия</h2>
            <ul class="mt-[13px] flex flex-col gap-5">
                <?php foreach ($actions as $action) { ?>
                    <li>
                        <a class="flex items-center gap-[15px]" href="#">
                            <span class="icon-tile">
                                <img
                                    src="<?= $img($action['icon']) ?>"
                                    width="<?= htmlspecialcharsbx($action['width']) ?>"
                                    height="<?= htmlspecialcharsbx($action['height']) ?>"
                                    alt=""
                                >
                            </span>
                            <span class="<?= htmlspecialcharsbx($action['labelClass']) ?>"><?= htmlspecialcharsbx($action['label']) ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </section>
        <section class="card flex flex-col gap-5 pt-6 pr-[25px] pb-[18px] pl-[21px]">
            <div class="flex flex-col gap-5">
                <div class="flex flex-col gap-[13px]">
                    <div class="flex items-center justify-between">
                        <h2 class="text-heading">Меню столовой</h2>
                        <img class="rotate-180" src="<?= $img('icon-chevron-menu.svg') ?>" width="20" height="20" alt="">
                    </div>
                    <div class="flex h-[52px] w-full">
                        <button type="button" class="flex h-[52px] w-[99px] flex-col justify-center gap-[5px] rounded-sm bg-accent pl-3 text-white shadow-[0_0_6.95px_rgba(0,0,0,0.25)]">
                            <span class="text-[12px] font-medium leading-[15px] tracking-[-0.8px]">Сегодня</span>
                            <span class="text-[10px] leading-[15px] tracking-[-0.8px]">22 июля</span>
                        </button>
                        <button type="button" class="flex h-[52px] w-[98px] flex-col justify-center gap-[5px] rounded-sm border border-border-soft bg-white pl-3 text-[#40406a]">
                            <span class="text-[12px] font-medium leading-[15px] tracking-[-0.8px]">Завтра</span>
                            <span class="text-[10px] leading-[15px] tracking-[-0.8px]">23 июля</span>
                        </button>
                        <button type="button" class="flex h-[52px] w-[60px] items-center justify-center rounded-sm border border-border-soft bg-white" aria-label="Календарь">
                            <img src="<?= $img('icon-calendar.svg') ?>" width="25" height="25" alt="">
                        </button>
                    </div>
                </div>
                <div class="flex flex-col gap-[5px]">
                    <p class="text-[15px] font-medium leading-7 tracking-[-0.8px] text-[var(--color-heading)]">Доступно сегодня</p>
                    <div class="flex w-full">
                        <button type="button" class="flex h-[38px] w-[128px] flex-col justify-center rounded-sm border border-accent bg-accent pl-3">
                            <span class="text-[12px] font-medium leading-[15px] tracking-[-0.8px] text-white">Обед</span>
                            <span class="text-[10px] leading-[15px] tracking-[-0.8px] text-[#d2d2ec]">12:00 - 15:00</span>
                        </button>
                        <button type="button" class="flex h-[38px] w-[131px] flex-col justify-center rounded-sm border border-border-soft bg-white pl-3">
                            <span class="text-[12px] font-medium leading-[15px] tracking-[-0.8px] text-[#40406a]">Другие блюда</span>
                            <span class="text-[10px] leading-[15px] tracking-[-0.8px] text-[var(--color-caption)]">в течение дня</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-[13px]">
                <div class="tracking-[-0.8px]">
                    <p class="text-[15px] font-medium leading-7 text-[var(--color-heading)]">Обеденое меню</p>
                    <p class="text-[10px] leading-[10px] text-[var(--color-caption)]">12:00-15:00</p>
                </div>
                <ul class="flex flex-col gap-2.5">
                    <?php foreach ($dishes as $dish) { ?>
                        <li class="flex items-start justify-between gap-2 rounded-sm border border-border-soft px-[14px] py-2">
                            <div>
                                <p class="text-[13px] font-medium leading-[15px] tracking-[-0.8px] whitespace-pre-line text-[var(--color-heading)]"><?= htmlspecialcharsbx($dish['title']) ?></p>
                                <p class="mt-px w-[135px] text-[10px] leading-[13px] tracking-[-0.8px] text-[var(--color-caption)]"><?= htmlspecialcharsbx($dish['text']) ?></p>
                                <p class="mt-[5px] text-[10px] font-medium leading-[10px] tracking-[-0.8px] text-[var(--color-heading)]">250 гр</p>
                            </div>
                            <p class="shrink-0 text-[13px] font-medium leading-[10px] tracking-[-0.8px] text-[var(--color-heading)]"><?= htmlspecialcharsbx($dish['price']) ?></p>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </section>
        </div>
    </aside>
    <main class="page-main">
