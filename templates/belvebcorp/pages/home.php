<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$img = static function (string $file): string {
    return htmlspecialcharsbx(SITE_TEMPLATE_PATH . '/images/' . $file);
};

$heroSlides = [
    [
        'image' => 'hero-bg.png',
        'title' => "Рады видеть Вас\nна внутреннем портале!",
        'day' => 'Сегодня',
        'date' => '29 августа 2026 г.',
        'time' => '11:36',
        'meetings' => 'Запланировано 3 встречи',
    ],
    [
        'image' => 'hero-bg.png',
        'title' => "Рады видеть Вас\nна внутреннем портале!",
        'day' => 'Сегодня',
        'date' => '29 августа 2026 г.',
        'time' => '11:36',
        'meetings' => 'Запланировано 3 встречи',
    ],
    [
        'image' => 'hero-bg.png',
        'title' => "Рады видеть Вас\nна внутреннем портале!",
        'day' => 'Сегодня',
        'date' => '29 августа 2026 г.',
        'time' => '11:36',
        'meetings' => 'Запланировано 3 встречи',
    ],
    [
        'image' => 'hero-bg.png',
        'title' => "Рады видеть Вас\nна внутреннем портале!",
        'day' => 'Сегодня',
        'date' => '29 августа 2026 г.',
        'time' => '11:36',
        'meetings' => 'Запланировано 3 встречи',
    ],
];

$newsCategories = ['Все новости', 'Безопасность', 'Маркетинг', 'Финансы'];

$newsCards = [
    [
        'image' => 'news-security.png',
        'tag' => 'Безопасность',
        'date' => '21.08.2026 г.',
        'title' => 'Обновление корпоративного портала: что нового?',
        'author' => 'Елена Коваль',
        'avatar' => 'avatar-elena.png',
        'likes' => '84',
        'comments' => '4',
        'views' => '127',
    ],
    [
        'image' => 'news-marketing.png',
        'tag' => 'Маркетинг',
        'date' => '05.07.2026 г.',
        'title' => 'Итоги стратегической сессии по развитию бренда',
        'author' => 'Андрей Круглый',
        'avatar' => 'avatar-andrey.png',
        'likes' => '84',
        'comments' => '4',
        'views' => '127',
    ],
];

$popularNews = [
    ['title' => 'Изменения в политике удаленной работы', 'date' => '25.07.2026 г.'],
    ['title' => 'Поздравляем победителей корпоративного марафона', 'date' => '20.07.2026 г.'],
    ['title' => 'Поздравляем победителей корпоративного марафона', 'date' => '20.07.2026 г.'],
];

$departments = [
    ['icon' => 'icon-dept-hr.svg', 'width' => '32', 'height' => '30', 'name' => 'HR', 'count' => '30 сотрудников'],
    ['icon' => 'icon-dept-it.svg', 'width' => '30', 'height' => '30', 'name' => 'IT', 'count' => '64 сотрудников'],
    ['icon' => 'icon-dept-retail.svg', 'width' => '34', 'height' => '34', 'name' => 'Розница', 'count' => '114 сотрудников'],
    ['icon' => 'icon-dept-security.svg', 'width' => '30', 'height' => '30', 'name' => 'Безопасность', 'count' => '28 сотрудников'],
    ['icon' => 'icon-dept-legal.svg', 'width' => '32', 'height' => '32', 'name' => 'Юридический', 'count' => '31 сотрудник'],
    ['icon' => 'icon-dept-support.svg', 'width' => '30', 'height' => '30', 'name' => 'Контакт-центр', 'count' => '131 сотрудник'],
    ['icon' => 'icon-dept-accounting.svg', 'width' => '29', 'height' => '29', 'name' => 'Бухгалтерия', 'count' => '26 сотрудников'],
    ['icon' => 'icon-dept-marketing.svg', 'width' => '30', 'height' => '30', 'name' => 'Маркетинг', 'count' => '18 сотрудников'],
];

$employees = [
    ['photo' => 'employee-sofia.png', 'name' => 'София Милославская', 'role' => 'Главный юрисконсульт', 'unit' => 'Юридический департамент'],
    ['photo' => 'employee-ksenia.png', 'name' => 'Ксения Шуманская', 'role' => 'Помощник юрисконсульт', 'unit' => 'Юридический департамент'],
    ['photo' => 'employee-dmitry.png', 'name' => 'Дмитрий Гулкевич', 'role' => 'Экономист розничного бизнеса', 'unit' => 'Розничный бизнес'],
    ['photo' => 'employee-alexey.png', 'name' => 'Алексей Сидоров', 'role' => 'Системный администратор Cisco', 'unit' => 'Департамент разработки'],
];

$events = [
    ['icon' => 'icon-birthday.svg', 'width' => '19', 'height' => '19', 'title' => 'Дни рождения', 'meta' => '+ 18 сегодня'],
    ['icon' => 'icon-person.svg', 'width' => '19', 'height' => '19', 'title' => 'Новые сотрудники', 'meta' => '+ 3 в этом месяце'],
    ['icon' => 'icon-palm.svg', 'width' => '20', 'height' => '20', 'title' => 'В отпуске', 'meta' => '23 сотрудника'],
];

$unions = [
    ['logo' => '', 'icon' => 'icon-heart-hands.svg', 'name' => 'Профсоюз', 'text' => 'Первичная профсоюзная организация ОАО «Банк БелВЭБ»'],
    ['logo' => 'union-brsm.png', 'icon' => '', 'name' => 'БРСМ', 'text' => 'Первичная организация ОО «БРСМ» ОАО «Банк БелВЭБ»'],
    ['logo' => 'union-belaya-rus.png', 'icon' => '', 'name' => '«Белая Русь»', 'text' => 'Республиканское общественное объединение «Белая Русь»'],
    ['logo' => 'union-bszh.png', 'icon' => '', 'name' => 'БСЖ', 'text' => 'Общественное объединение «Белорусский союз женщин»'],
    ['logo' => 'union-boov.png', 'icon' => '', 'name' => 'БООВ', 'text' => 'Белорусское общественное объединение ветеранов'],
];

$leisure = [
    ['image' => 'leisure-play.png', 'tag' => 'Спектакль', 'title' => '«Дом Бернарды Альбы»', 'date' => '31 сентября, 11:00', 'place' => 'Новый театр, ул. Л. Чайкиной, 16'],
    ['image' => 'leisure-tour.png', 'tag' => 'Экскурсия', 'title' => 'Экскурсия на фабрику «Слодыч»', 'date' => '31 сентября, 11:00', 'place' => 'ул. Радиальная, 54'],
    ['image' => 'leisure-sport.png', 'tag' => 'Спорт', 'title' => 'Динамо-Минск vs Торпедо', 'date' => '31 сентября, 11:00', 'place' => 'Минск-Арена, пр-т Победителей, 111'],
    ['image' => 'leisure-love.png', 'tag' => 'Спектакль', 'title' => '«Оживляя любовь»', 'date' => '31 сентября, 11:00', 'place' => 'индустриальный парк Великий Камень, Пекинский пр-т, 29'],
];

$photos = [
    ['image' => 'photo-training.png', 'tag' => 'Обучение', 'title' => 'Обучение для сотрудников', 'date' => '01 сентября 2026', 'place' => 'Минск, пр-т Газеты Правда, 11'],
    ['image' => 'photo-team.png', 'tag' => 'Тимбилдинг', 'title' => 'Тимбилдинг команды', 'date' => '31 августа 2026', 'place' => 'Минское море'],
    ['image' => 'photo-expo.png', 'tag' => 'Выставка', 'title' => 'Участие в выставке', 'date' => '02 сентября 2026', 'place' => 'ул. Павлины Меделки, 24'],
    ['image' => 'photo-party.png', 'tag' => 'Корпоратив', 'title' => 'Корпоративное мероприятие', 'date' => '20 сентября 2026', 'place' => 'Конгресс Холл'],
];

$discountCategories = ['Все скидки', 'Еда', 'Спорт', 'Развлечения'];

$discounts = [
    ['image' => 'discount-burger.png', 'name' => 'Burger King', 'offer' => 'до 20%', 'until' => 'до 31 августа'],
    ['image' => 'discount-ozon.png', 'name' => 'Ozon', 'offer' => 'до 10%', 'until' => 'до 31 августа'],
    ['image' => 'discount-sport.png', 'name' => 'Спортмастер', 'offer' => 'до 15%', 'until' => 'до 115 августа'],
    ['image' => 'discount-gym.png', 'name' => 'GYM24', 'offer' => 'до 10%', 'until' => 'до 21 августа'],
];

$shopCategories = ['Все товары', 'Канцелярия', 'Техника', 'Сувениры'];

$products = [
    ['image' => 'shop-hoodie.png', 'name' => 'Худи с логотипом', 'price' => '350 баллов'],
    ['image' => 'shop-mug.png', 'name' => 'Термокружка', 'price' => '150 баллов'],
    ['image' => 'shop-powerbank.png', 'name' => 'Повербанк 10000 mAh', 'price' => '250 баллов'],
    ['image' => 'shop-notebook.png', 'name' => 'Ежедневник', 'price' => '50 баллов'],
];

$outlineButton = 'btn h-10 rounded-md border border-accent/60 text-[20px] font-medium tracking-[-0.8px] text-[var(--color-heading)]';
?>
<link rel="stylesheet" href="<?= htmlspecialcharsbx(SITE_TEMPLATE_PATH) ?>/js/swiper/swiper-bundle.min.css">
<div class="home">
    <section class="swiper hero-swiper" aria-label="Приветствие">
        <div class="swiper-wrapper">
            <?php foreach ($heroSlides as $slide) { ?>
                <div class="swiper-slide">
                    <img src="<?= $img($slide['image']) ?>" alt="">
                    <div class="hero-copy">
                        <h1 class="hero-title whitespace-pre-line"><?= htmlspecialcharsbx($slide['title']) ?></h1>
                        <div class="mt-[90px] flex h-[95px] w-[305px] items-center gap-[18px] rounded-md bg-white px-4">
                            <span class="inline-flex size-[70px] shrink-0 items-center justify-center rounded-sm bg-[var(--color-tint)]">
                                <img src="<?= $img('icon-calendar-lg.svg') ?>" width="39" height="39" alt="">
                            </span>
                            <div>
                                <p class="text-title"><?= htmlspecialcharsbx($slide['day']) ?></p>
                                <p class="text-[15px] leading-[12px] tracking-[-0.18px] text-[var(--color-muted)]"><?= htmlspecialcharsbx($slide['date']) ?></p>
                                <p class="text-[15px] leading-[12px] text-[var(--color-muted-soft)]"><?= htmlspecialcharsbx($slide['time']) ?></p>
                                <p class="mt-1 flex items-center gap-[5px] text-[15px] leading-[12px] tracking-[-0.18px] whitespace-nowrap text-[var(--color-accent)]">
                                    <img src="<?= $img('icon-dot.svg') ?>" width="4" height="4" alt="">
                                    <?= htmlspecialcharsbx($slide['meetings']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="hero-pagination swiper-pagination"></div>
    </section>

    <section class="card flex gap-[25px] px-[27px] pt-5 pb-[18px]" aria-labelledby="news-title">
        <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between gap-5">
                <h2 id="news-title" class="section-title">Новости компании</h2>
                <div class="flex items-center gap-[10px]">
                    <?php foreach ($newsCategories as $index => $category) { ?>
                        <a class="chip<?= $index === 0 ? ' chip-active' : '' ?>" href="#"><?= htmlspecialcharsbx($category) ?></a>
                    <?php } ?>
                </div>
            </div>
            <div class="mt-[23px] flex gap-5">
                <?php foreach ($newsCards as $card) { ?>
                    <article class="news-card">
                        <img src="<?= $img($card['image']) ?>" alt="">
                        <span class="absolute top-[9px] right-3 z-[1] inline-flex h-[31px] items-center rounded-md bg-white px-6 text-[13px] leading-5 tracking-[-0.8px] text-[var(--color-accent)]"><?= htmlspecialcharsbx($card['tag']) ?></span>
                        <div class="news-card-body">
                            <p class="text-[15px] font-medium leading-5 tracking-[-0.8px] text-white/60"><?= htmlspecialcharsbx($card['date']) ?></p>
                            <h3 class="mt-[5px] w-[273px] text-[20px] font-medium leading-[22px] tracking-[-0.8px]"><?= htmlspecialcharsbx($card['title']) ?></h3>
                            <div class="mt-[15px] flex items-center justify-between gap-3">
                                <p class="flex items-center gap-[10px] text-[20px] font-medium leading-5 tracking-[-0.8px]">
                                    <img class="size-[25px] rounded-full object-cover" src="<?= $img($card['avatar']) ?>" width="25" height="25" alt="">
                                    <?= htmlspecialcharsbx($card['author']) ?>
                                </p>
                                <p class="flex items-center gap-[3px] text-[10px] font-medium leading-5 tracking-[-0.8px] text-[var(--color-border)]">
                                    <img src="<?= $img('icon-like.svg') ?>" width="15" height="15" alt="">
                                    <?= htmlspecialcharsbx($card['likes']) ?>
                                    <img class="ml-1" src="<?= $img('icon-comment.svg') ?>" width="15" height="15" alt="">
                                    <?= htmlspecialcharsbx($card['comments']) ?>
                                    <img class="ml-1" src="<?= $img('icon-eye.svg') ?>" width="15" height="15" alt="">
                                    <?= htmlspecialcharsbx($card['views']) ?>
                                </p>
                            </div>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </div>
        <div class="flex w-[260px] shrink-0 flex-col border-l border-[var(--color-border)] pl-[25px]">
            <h2 class="text-[25px] font-medium leading-5 tracking-[-0.8px] text-[var(--color-heading)]">Популярные новости</h2>
            <div class="mt-[15px] flex flex-col gap-[15px]">
                <?php foreach ($popularNews as $item) { ?>
                    <a href="#">
                        <p class="text-[18px] font-medium leading-[21px] tracking-[-0.8px] text-[var(--color-heading)]"><?= htmlspecialcharsbx($item['title']) ?></p>
                        <p class="mt-[5px] text-[13px] font-medium leading-5 tracking-[-0.8px] text-[#848383]"><?= htmlspecialcharsbx($item['date']) ?></p>
                    </a>
                <?php } ?>
            </div>
            <a class="<?= $outlineButton ?> mt-4 w-[222px]" href="#">Все новости</a>
        </div>
    </section>

    <section class="card px-7 pt-[26px] pb-[22px]" aria-labelledby="departments-title">
        <div class="flex items-center justify-between gap-5">
            <div class="flex items-center gap-[35px]">
                <div class="flex items-center gap-[25px]">
                    <span class="section-icon"><img src="<?= $img('icon-users.svg') ?>" width="24" height="24" alt=""></span>
                    <h2 id="departments-title" class="section-title">Подразделения</h2>
                </div>
                <form class="relative h-10 w-[289px]" action="#" method="get">
                    <input class="h-[39px] w-full rounded-md border border-[var(--color-border-soft)] bg-white pr-14 pl-[15px] text-[15px] text-[rgba(0,0,0,0.57)]" type="search" name="q" placeholder="Поиск подразделения..." aria-label="Поиск подразделения">
                    <button class="absolute top-1 right-2 inline-flex h-[30px] w-10 items-center justify-center rounded-md bg-[#4470e2]" type="submit" aria-label="Найти">
                        <img src="<?= $img('icon-search-dept.svg') ?>" width="17" height="17" alt="">
                    </button>
                </form>
            </div>
            <a class="<?= $outlineButton ?> w-[230px]" href="#">Все подразделения</a>
        </div>
        <div class="mt-[17px] grid grid-cols-4 gap-x-[35px] gap-y-[18px]">
            <?php foreach ($departments as $department) { ?>
                <a class="flex h-20 w-[240px] items-center gap-[15px] rounded-sm border border-[var(--color-border-soft)] px-[13px]" href="#">
                    <span class="dept-tile"><img src="<?= $img($department['icon']) ?>" width="<?= htmlspecialcharsbx($department['width']) ?>" height="<?= htmlspecialcharsbx($department['height']) ?>" alt=""></span>
                    <span>
                        <span class="block text-title text-[var(--color-foreground)]"><?= htmlspecialcharsbx($department['name']) ?></span>
                        <span class="mt-[5px] block text-[15px] leading-3 tracking-[-0.18px] text-[var(--color-caption)]"><?= htmlspecialcharsbx($department['count']) ?></span>
                    </span>
                </a>
            <?php } ?>
        </div>
    </section>

    <div class="flex items-start gap-[17px]">
        <section class="card w-[813px] px-8 pt-[14px] pb-[27px]" aria-labelledby="employees-title">
            <div class="flex items-center gap-5">
                <span class="section-icon"><img src="<?= $img('icon-user.svg') ?>" width="22" height="22" alt=""></span>
                <h2 id="employees-title" class="section-title">Сотрудники</h2>
            </div>
            <p class="mt-[6px] text-[15px] leading-[18px] tracking-[-0.8px] text-[rgba(13,12,84,0.7)]">Поздравьте коллег С Днем Рождения!</p>
            <div class="mt-[15px] grid grid-cols-2 gap-x-[30px] gap-y-[26px]">
                <?php foreach ($employees as $employee) { ?>
                    <article class="flex items-center gap-[21px]">
                        <img class="size-[110px] shrink-0 rounded-full object-cover" src="<?= $img($employee['photo']) ?>" width="110" height="110" alt="">
                        <div class="min-w-0">
                            <h3 class="text-title text-[var(--color-foreground)]"><?= htmlspecialcharsbx($employee['name']) ?></h3>
                            <p class="mt-[5px] text-[15px] leading-3 tracking-[-0.18px] text-[var(--color-foreground)]"><?= htmlspecialcharsbx($employee['role']) ?></p>
                            <p class="mt-[10px] text-[18px] leading-3 tracking-[-0.18px] text-[var(--color-muted)]"><?= htmlspecialcharsbx($employee['unit']) ?></p>
                            <div class="mt-5 flex items-center gap-[22px]">
                                <span class="flex items-center gap-[9px]">
                                    <a href="#" aria-label="Почта"><img src="<?= $img('icon-mail.svg') ?>" width="23" height="23" alt=""></a>
                                    <a href="#" aria-label="Чат"><img src="<?= $img('icon-chat.svg') ?>" width="23" height="23" alt=""></a>
                                    <a href="#" aria-label="Телефон"><img src="<?= $img('icon-phone-outline.svg') ?>" width="23" height="23" alt=""></a>
                                </span>
                                <a class="btn btn-primary-sm w-[105px]" href="#">Поздравить</a>
                            </div>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </section>
        <section class="card flex h-[382px] w-[310px] flex-col px-[17px] pt-[26px] pb-5" aria-labelledby="events-title">
            <div class="flex gap-[65px] text-[20px] font-medium leading-5 tracking-[-0.8px] text-[var(--color-heading)]">
                <h2 id="events-title">События</h2>
                <a href="#">Календарь</a>
            </div>
            <div class="mt-[9px] h-px bg-[var(--color-border)]">
                <span class="block h-0.5 w-[118px] bg-[var(--color-accent)]"></span>
            </div>
            <ul class="mt-5 flex flex-col gap-5">
                <?php foreach ($events as $event) { ?>
                    <li class="flex items-center gap-[10px]">
                        <span class="inline-flex size-[30px] shrink-0 items-center justify-center rounded-sm bg-[var(--color-tint)]">
                            <img src="<?= $img($event['icon']) ?>" width="<?= htmlspecialcharsbx($event['width']) ?>" height="<?= htmlspecialcharsbx($event['height']) ?>" alt="">
                        </span>
                        <span class="text-[18px] font-medium leading-5 tracking-[-0.18px] text-[var(--color-heading)]"><?= htmlspecialcharsbx($event['title']) ?></span>
                        <span class="ml-auto text-[15px] font-medium leading-4 tracking-[-0.18px] text-[var(--color-accent)]"><?= htmlspecialcharsbx($event['meta']) ?></span>
                    </li>
                <?php } ?>
            </ul>
            <a class="<?= $outlineButton ?> mt-auto w-full" href="#">Все события</a>
        </section>
    </div>

    <section class="card px-[35px] pt-[22px] pb-[38px]" aria-labelledby="unions-title">
        <div class="flex items-center justify-between gap-5">
            <div class="flex items-center gap-5">
                <span class="section-icon"><img src="<?= $img('icon-union.svg') ?>" width="21" height="21" alt=""></span>
                <h2 id="unions-title" class="text-[25px] font-medium leading-6 text-[var(--color-heading)]">Общественные объединения</h2>
            </div>
            <a class="btn h-[45px] gap-[6px] rounded-md border border-[#e2e8f0] px-3 text-[14px] font-medium leading-5 text-[#6366f1]" href="#">
                <img src="<?= $img('icon-help-circle.svg') ?>" width="15" height="15" alt="">
                Как вступить?
            </a>
        </div>
        <div class="mt-[25px] flex gap-[15px]">
            <?php foreach ($unions as $union) { ?>
                <a class="flex h-[200px] w-[205px] flex-col rounded-xl border border-[var(--color-border)] bg-[var(--color-canvas)] p-4" href="#">
                    <span class="inline-flex size-[55px] items-center justify-center overflow-hidden rounded-xl bg-white">
                        <?php if ($union['logo'] !== '') { ?>
                            <img src="<?= $img($union['logo']) ?>" width="47" height="47" alt="">
                        <?php } else { ?>
                            <img src="<?= $img($union['icon']) ?>" width="33" height="33" alt="">
                        <?php } ?>
                    </span>
                    <span class="mt-3 text-[14px] font-medium leading-5 text-[var(--color-foreground)]"><?= htmlspecialcharsbx($union['name']) ?></span>
                    <span class="mt-1 line-clamp-3 text-[12px] leading-[16.5px] text-[var(--color-muted)]"><?= htmlspecialcharsbx($union['text']) ?></span>
                    <span class="mt-auto flex items-center gap-0.5 text-[12px] font-medium leading-4 text-[#6366f1] opacity-70">
                        Подробнее
                        <img src="<?= $img('icon-chevron-right.svg') ?>" width="13" height="13" alt="">
                    </span>
                </a>
            <?php } ?>
        </div>
    </section>

    <section class="card px-[35px] pt-[23px] pb-[35px]" aria-labelledby="leisure-title">
        <div class="flex items-center gap-[25px]">
            <span class="section-icon"><img src="<?= $img('icon-mask.svg') ?>" width="24" height="24" alt=""></span>
            <h2 id="leisure-title" class="section-title">Досуг</h2>
        </div>
        <div class="mt-[15px] flex gap-5">
            <?php foreach ($leisure as $item) { ?>
                <article class="event-card">
                    <div class="event-card-media">
                        <img src="<?= $img($item['image']) ?>" alt="">
                        <span class="absolute top-[10px] left-2 inline-flex h-[25px] items-center rounded-md bg-[var(--color-canvas)] px-3 text-[13px] leading-5 tracking-[-0.8px] text-[var(--color-foreground)]"><?= htmlspecialcharsbx($item['tag']) ?></span>
                    </div>
                    <div class="event-card-body">
                        <h3 class="text-[15px] font-medium leading-4 tracking-[-0.18px] text-[var(--color-heading)]"><?= htmlspecialcharsbx($item['title']) ?></h3>
                        <p class="mt-[10px] flex items-center gap-1 text-[13px] leading-3 tracking-[-0.18px] text-[#40406a]">
                            <img src="<?= $img('icon-calendar-sm.svg') ?>" width="15" height="15" alt="">
                            <?= htmlspecialcharsbx($item['date']) ?>
                        </p>
                        <p class="mt-[10px] flex items-start gap-1 text-[11px] leading-3 tracking-[-0.18px] text-[#40406a]">
                            <img class="mt-px shrink-0" src="<?= $img('icon-pin.svg') ?>" width="16" height="13" alt="">
                            <?= htmlspecialcharsbx($item['place']) ?>
                        </p>
                        <a class="btn mt-auto h-10 w-full rounded-md bg-[var(--color-accent)] text-[15px] font-medium tracking-[-0.8px] text-white" href="#">Подробнее</a>
                    </div>
                </article>
            <?php } ?>
        </div>
    </section>

    <section class="card py-[23px] pl-[31px] pr-[31px]" aria-labelledby="photos-title">
        <div class="flex items-center gap-[25px]">
            <span class="section-icon"><img src="<?= $img('icon-gallery.svg') ?>" width="25" height="25" alt=""></span>
            <h2 id="photos-title" class="section-title">Фото компании</h2>
        </div>
        <form class="mt-[10px] flex items-end gap-[19px]" action="#" method="get">
            <label class="block">
                <span class="text-[15px] font-medium text-[var(--color-heading)]">Дата проведения</span>
                <span class="mt-1 flex h-[45px] w-[230px] items-center justify-between rounded-md border border-[var(--color-border)] px-3 text-[13px] tracking-[-0.1px] text-[var(--color-muted)]">
                    Выбрать дату
                    <img src="<?= $img('icon-calendar-input.svg') ?>" width="20" height="20" alt="">
                </span>
            </label>
            <button class="btn h-[45px] w-[111px] rounded-md border border-[var(--color-primary)] text-[15px] font-medium text-[var(--color-primary)]" type="button">Применить</button>
        </form>
        <p class="mt-[10px] flex items-center gap-[13px] text-[15px] leading-[18px] tracking-[-0.8px] text-[rgba(13,12,84,0.7)]">
            <span class="inline-flex h-[30px] items-center gap-[5px] rounded-md bg-[var(--color-border)] px-[7px] text-[13px] leading-5 tracking-[-0.8px] text-[var(--color-foreground)]">
                Прошлая неделя
                <img src="<?= $img('icon-cross.svg') ?>" width="13" height="13" alt="">
            </span>
            Найдено: <span class="font-medium">2</span> события
        </p>
        <div class="mt-4 flex gap-5">
            <?php foreach ($photos as $item) { ?>
                <article class="event-card">
                    <div class="event-card-media">
                        <img src="<?= $img($item['image']) ?>" alt="">
                        <span class="absolute top-[10px] left-2 inline-flex h-[25px] items-center rounded-md bg-[var(--color-canvas)] px-2 text-[10px] leading-5 tracking-[-0.8px] text-[var(--color-foreground)]"><?= htmlspecialcharsbx($item['tag']) ?></span>
                    </div>
                    <div class="event-card-body">
                        <h3 class="text-[15px] font-medium leading-4 tracking-[-0.18px] text-[var(--color-heading)]"><?= htmlspecialcharsbx($item['title']) ?></h3>
                        <p class="mt-[10px] flex items-center gap-1 text-[11px] leading-3 tracking-[-0.18px] text-[#40406a]">
                            <img src="<?= $img('icon-calendar-sm.svg') ?>" width="15" height="15" alt="">
                            <?= htmlspecialcharsbx($item['date']) ?>
                        </p>
                        <p class="mt-[10px] flex items-start gap-1 text-[11px] leading-3 tracking-[-0.18px] text-[#40406a]">
                            <img class="mt-px shrink-0" src="<?= $img('icon-pin.svg') ?>" width="16" height="13" alt="">
                            <?= htmlspecialcharsbx($item['place']) ?>
                        </p>
                        <a class="btn mt-auto h-10 w-full rounded-md bg-[var(--color-accent)] text-[15px] font-medium tracking-[-0.8px] text-white" href="#">Перейти в галерею</a>
                    </div>
                </article>
            <?php } ?>
        </div>
    </section>

    <section class="card px-8 py-[30px]" aria-labelledby="discounts-title">
        <div class="flex items-center justify-between gap-5">
            <div class="flex items-center gap-[22px]">
                <div class="flex items-center gap-[25px]">
                    <span class="section-icon"><img src="<?= $img('icon-sale.svg') ?>" width="24" height="24" alt=""></span>
                    <h2 id="discounts-title" class="section-title">Скидки банка</h2>
                </div>
                <div class="flex items-center gap-[10px]">
                    <?php foreach ($discountCategories as $index => $category) { ?>
                        <a class="chip<?= $index === 0 ? ' chip-active' : '' ?>" href="#"><?= htmlspecialcharsbx($category) ?></a>
                    <?php } ?>
                </div>
            </div>
            <a class="<?= $outlineButton ?> w-[190px]" href="#">Все скидки банка</a>
        </div>
        <div class="mt-[25px] flex gap-[15px]">
            <?php foreach ($discounts as $discount) { ?>
                <a class="flex h-[90px] w-[260px] items-center gap-4 rounded-sm border border-[var(--color-border-soft)] px-5" href="#">
                    <img class="size-[60px] rounded-sm object-cover" src="<?= $img($discount['image']) ?>" width="60" height="60" alt="">
                    <span>
                        <span class="block text-[20px] font-medium leading-[23px] tracking-[-0.18px] text-[var(--color-heading)]"><?= htmlspecialcharsbx($discount['name']) ?></span>
                        <span class="block text-[18px] font-medium leading-[23px] tracking-[-0.18px] text-[var(--color-accent)]"><?= htmlspecialcharsbx($discount['offer']) ?></span>
                        <span class="block text-[12px] leading-3 tracking-[-0.18px] text-[var(--color-caption)]"><?= htmlspecialcharsbx($discount['until']) ?></span>
                    </span>
                </a>
            <?php } ?>
        </div>
    </section>

    <section class="card px-8 pt-5 pb-[30px]" aria-labelledby="shop-title">
        <div class="flex items-center justify-between gap-5">
            <div class="flex items-center gap-[25px]">
                <div class="flex items-center gap-[25px]">
                    <span class="section-icon"><img src="<?= $img('icon-bag.svg') ?>" width="24" height="24" alt=""></span>
                    <h2 id="shop-title" class="section-title">Магазин</h2>
                </div>
                <div class="flex items-center gap-[10px]">
                    <?php foreach ($shopCategories as $index => $category) { ?>
                        <a class="chip<?= $index === 0 ? ' chip-active' : '' ?>" href="#"><?= htmlspecialcharsbx($category) ?></a>
                    <?php } ?>
                </div>
            </div>
            <a class="<?= $outlineButton ?> w-[210px]" href="#">Перейти в магазин</a>
        </div>
        <div class="mt-[25px] flex gap-[14px]">
            <?php foreach ($products as $product) { ?>
                <a class="flex h-[97px] w-[260px] items-center gap-2 rounded-sm border border-[var(--color-border-soft)] px-[5px]" href="#">
                    <img class="size-[82px] object-cover" src="<?= $img($product['image']) ?>" width="82" height="82" alt="">
                    <span>
                        <span class="block text-[15px] font-medium leading-[23px] tracking-[-0.18px] text-[var(--color-heading)]"><?= htmlspecialcharsbx($product['name']) ?></span>
                        <span class="block text-[13px] leading-3 tracking-[-0.18px] text-[var(--color-caption)]"><?= htmlspecialcharsbx($product['price']) ?></span>
                    </span>
                </a>
            <?php } ?>
        </div>
    </section>
</div>
<script src="<?= htmlspecialcharsbx(SITE_TEMPLATE_PATH) ?>/js/swiper/swiper-bundle.min.js"></script>
<script>
    new Swiper('.hero-swiper', {
        slidesPerView: 1,
        pagination: {
            el: '.hero-pagination',
            clickable: true,
        },
        observer: true,
        observeParents: true,
        resizeObserver: true,
    });
</script>
