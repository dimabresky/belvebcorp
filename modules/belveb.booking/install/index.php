<?php

use Belveb\Booking\Install\HighloadInstaller;
use Belveb\Booking\Install\IblockInstaller;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class belveb_booking extends CModule
{
    public $MODULE_ID = 'belveb.booking';

    public $MODULE_VERSION;

    public $MODULE_VERSION_DATE;

    public $MODULE_NAME;

    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('BELVEB_BOOKING_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('BELVEB_BOOKING_MODULE_DESCRIPTION');
    }

    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);
        if (!$this->installDB()) {
            ModuleManager::unRegisterModule($this->MODULE_ID);

            return;
        }
        $this->installFiles();
    }

    public function DoUninstall(): void
    {
        global $APPLICATION, $step;

        if ((int) $step < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('BELVEB_BOOKING_UNINSTALL_TITLE'),
                __DIR__ . '/unstep1.php'
            );

            return;
        }

        if (!$this->unInstallDB([
            'savedata' => ($_REQUEST['savedata'] ?? '') === 'Y',
        ])) {
            return;
        }

        $this->unInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installDB(): bool
    {
        $this->loadInstallers();

        try {
            (new HighloadInstaller())->install();
            (new IblockInstaller())->install();
        } catch (\Throwable $exception) {
            global $APPLICATION;
            if (is_object($APPLICATION)) {
                $APPLICATION->ThrowException($exception->getMessage());
            }

            return false;
        }

        return true;
    }

    /**
     * @param array<string, mixed> $arParams
     */
    public function unInstallDB($arParams = []): bool
    {
        $this->loadInstallers();
        $keepData = is_array($arParams) && !empty($arParams['savedata']);

        try {
            (new IblockInstaller())->uninstall($keepData);
            (new HighloadInstaller())->uninstall($keepData);
        } catch (\Throwable $exception) {
            global $APPLICATION;
            if (is_object($APPLICATION)) {
                $APPLICATION->ThrowException($exception->getMessage());
            }

            return false;
        }

        return true;
    }

    private function loadInstallers(): void
    {
        require_once dirname(__DIR__) . '/lib/Install/HighloadInstaller.php';
        require_once dirname(__DIR__) . '/lib/Install/IblockInstaller.php';
    }

    public function installFiles(): bool
    {
        CopyDirFiles(
            __DIR__ . '/components',
            $_SERVER['DOCUMENT_ROOT'] . '/local/components',
            true,
            true
        );
        CopyDirFiles(
            __DIR__ . '/js',
            $_SERVER['DOCUMENT_ROOT'] . '/local/js',
            true,
            true
        );
        CopyDirFiles(
            __DIR__ . '/public',
            $_SERVER['DOCUMENT_ROOT'],
            true,
            true
        );

        if (class_exists(\Bitrix\Main\UrlRewriter::class)) {
            \Bitrix\Main\UrlRewriter::delete(SITE_ID, ['ID' => 'belveb:booking']);
            \Bitrix\Main\UrlRewriter::add(SITE_ID, [
                'CONDITION' => '#^/booking/#',
                'RULE' => '',
                'ID' => 'belveb:booking',
                'PATH' => '/booking/index.php',
            ]);
        }

        return true;
    }

    public function unInstallFiles(): bool
    {
        DeleteDirFilesEx('/local/components/belveb/booking');
        DeleteDirFilesEx('/local/components/belveb/booking.rooms');
        DeleteDirFilesEx('/local/components/belveb/booking.my');
        DeleteDirFilesEx('/local/components/belveb/booking.room');
        DeleteDirFilesEx('/local/js/belveb');
        if (class_exists(\Bitrix\Main\UrlRewriter::class)) {
            \Bitrix\Main\UrlRewriter::delete(SITE_ID, ['ID' => 'belveb:booking']);
        }
        DeleteDirFiles(__DIR__ . '/public', $_SERVER['DOCUMENT_ROOT']);

        return true;
    }
}
