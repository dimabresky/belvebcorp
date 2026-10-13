<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class BelvebBooking extends CBitrixComponent
{
    public function executeComponent(): void
    {
        global $APPLICATION;

        $APPLICATION->AddChainItem('Бронирование', '/booking/');

        $page = $this->page();
        $this->arResult['PAGE'] = $page['name'];
        $this->arResult['ROOM_CODE'] = $page['code'];

        $this->includeComponentTemplate($page['name']);
    }

    /**
     * @return array{name: string, code: string}
     */
    private function page(): array
    {
        $forced = (string) ($this->arParams['PAGE'] ?? '');
        if ($forced === 'my' || $forced === 'rooms') {
            return ['name' => $forced, 'code' => ''];
        }

        $path = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
        $folder = (string) ($this->arParams['SEF_FOLDER'] ?? '/booking/');
        $relative = trim(substr($path, strlen(rtrim($folder, '/'))), '/');

        if ($relative === '' || $relative === 'index.php') {
            return ['name' => 'rooms', 'code' => ''];
        }
        if ($relative === 'my') {
            return ['name' => 'my', 'code' => ''];
        }

        return ['name' => 'room', 'code' => $relative];
    }
}
