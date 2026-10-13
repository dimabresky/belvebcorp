<?php

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
        $this->installFiles();
    }

    public function DoUninstall(): void
    {
        $this->unInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installDB(): bool
    {
        return true;
    }

    public function unInstallDB(): bool
    {
        return true;
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
            __DIR__ . '/public',
            $_SERVER['DOCUMENT_ROOT'],
            true,
            true
        );

        return true;
    }

    public function unInstallFiles(): bool
    {
        DeleteDirFilesEx('/local/components/belveb/booking.rooms');
        DeleteDirFiles(__DIR__ . '/public', $_SERVER['DOCUMENT_ROOT']);

        return true;
    }
}
