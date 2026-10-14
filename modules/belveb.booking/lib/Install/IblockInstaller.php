<?php

namespace Belveb\Booking\Install;

use Bitrix\Main\Loader;
use RuntimeException;

/**
 * Creates the booking iblock type, room catalog, and booking catalog.
 */
final class IblockInstaller
{
    private const TYPE_ID = 'belveb_booking';

    public function install(): void
    {
        if (!Loader::includeModule('iblock')) {
            throw new RuntimeException('Module iblock is required');
        }

        $this->installType();
        $roomsId = $this->installRooms();
        $this->installBookings($roomsId);
    }

    public function uninstall(bool $keepData): void
    {
        if ($keepData || !Loader::includeModule('iblock')) {
            return;
        }

        $this->deleteIblock('belveb_bookings');
        $this->deleteIblock('belveb_booking_rooms');
        $this->deleteType();
    }

    private function installType(): void
    {
        $found = \CIBlockType::GetByID(self::TYPE_ID)->Fetch();
        if (is_array($found)) {
            return;
        }

        $type = new \CIBlockType();
        $added = $type->Add([
            'ID' => self::TYPE_ID,
            'SECTIONS' => 'N',
            'IN_RSS' => 'N',
            'SORT' => 500,
            'LANG' => [
                'ru' => [
                    'NAME' => 'Бронирование',
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Элемент',
                ],
                'en' => [
                    'NAME' => 'Booking',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Element',
                ],
            ],
        ]);
        if (!$added) {
            throw new RuntimeException('Iblock type was not created. ' . $type->LAST_ERROR);
        }
    }

    private function installRooms(): int
    {
        $id = $this->iblock(
            'belveb_booking_rooms',
            'bookingRooms',
            'Переговорные',
            '#SITE_DIR#booking/#CODE#/',
            100
        );
        $this->ensureProperty($id, $this->stringProperty('LABEL', 'Подпись', true, 100));
        $this->ensureProperty($id, $this->numberProperty('FLOOR', 'Этаж', true, 200));
        $this->ensureProperty($id, $this->numberProperty('CAPACITY', 'Вместимость', true, 300));
        $this->ensureProperty($id, $this->directoryProperty(
            'TYPE',
            'Тип переговорной',
            'belveb_booking_room_type',
            true,
            false,
            400
        ));
        $this->ensureProperty($id, $this->directoryProperty(
            'AMENITIES',
            'Оборудование',
            'belveb_booking_amenity',
            false,
            true,
            500
        ));
        $this->ensureProperty($id, $this->stringProperty('WORK_FROM', 'Начало рабочего дня', false, 600));
        $this->ensureProperty($id, $this->stringProperty('WORK_TO', 'Конец рабочего дня', false, 700));
        $this->ensureProperty($id, $this->numberProperty('SLOT_STEP', 'Шаг сетки, минут', false, 800));

        return $id;
    }

    private function installBookings(int $roomsId): void
    {
        $id = $this->iblock('belveb_bookings', 'bookings', 'Бронирования', '', 200);
        $this->ensureProperty($id, $this->elementProperty('ROOM', 'Комната', $roomsId, true, false, 100));
        $this->ensureProperty($id, $this->userProperty('ORGANIZER', 'Организатор', true, false, 200));
        $this->ensureProperty($id, $this->userProperty('PARTICIPANTS', 'Участники', false, true, 300));
        $this->ensureProperty($id, $this->dateTimeProperty('DATE_FROM', 'Начало', true, 400));
        $this->ensureProperty($id, $this->dateTimeProperty('DATE_TO', 'Окончание', true, 500));
        $this->ensureProperty($id, $this->listProperty('STATUS', 'Статус', true, 600, [
            ['XML_ID' => 'booked', 'VALUE' => 'Забронировано', 'DEF' => 'Y', 'SORT' => 100],
            ['XML_ID' => 'completed', 'VALUE' => 'Завершена', 'DEF' => 'N', 'SORT' => 200],
            ['XML_ID' => 'cancelled', 'VALUE' => 'Отменена', 'DEF' => 'N', 'SORT' => 300],
        ]));
        $this->ensureProperty($id, $this->listProperty('REPEAT', 'Повтор', false, 700, [
            ['XML_ID' => 'none', 'VALUE' => 'Без повтора', 'DEF' => 'Y', 'SORT' => 100],
            ['XML_ID' => 'daily', 'VALUE' => 'Ежедневно', 'DEF' => 'N', 'SORT' => 200],
            ['XML_ID' => 'weekly', 'VALUE' => 'Еженедельно', 'DEF' => 'N', 'SORT' => 300],
            ['XML_ID' => 'monthly', 'VALUE' => 'Ежемесячно', 'DEF' => 'N', 'SORT' => 400],
        ]));
        $this->ensureProperty($id, $this->dateProperty('REPEAT_FROM', 'Повтор с', 800));
        $this->ensureProperty($id, $this->dateProperty('REPEAT_UNTIL', 'Повтор до', 900));
        $this->ensureProperty($id, $this->elementProperty('SERIES', 'Серия', $id, false, false, 1000));
        $this->ensureProperty($id, $this->elementProperty('SOURCE', 'Источник', $id, false, false, 1100));
        $this->ensureProperty($id, $this->dateTimeProperty('CANCELLED_AT', 'Отменена', false, 1200));
        $this->ensureProperty($id, $this->userProperty('CANCELLED_BY', 'Отменил', false, false, 1300));
    }

    private function iblock(string $code, string $apiCode, string $name, string $detailUrl, int $sort): int
    {
        $found = \CIBlock::GetList([], [
            'TYPE' => self::TYPE_ID,
            'CODE' => $code,
            'CHECK_PERMISSIONS' => 'N',
        ])->Fetch();
        if (is_array($found)) {
            return (int) $found['ID'];
        }

        $iblock = new \CIBlock();
        $id = $iblock->Add([
            'ACTIVE' => 'Y',
            'NAME' => $name,
            'CODE' => $code,
            'API_CODE' => $apiCode,
            'IBLOCK_TYPE_ID' => self::TYPE_ID,
            'SITE_ID' => [$this->siteId()],
            'SORT' => $sort,
            'GROUP_ID' => [2 => 'R', 1 => 'W'],
            'VERSION' => 2,
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'WORKFLOW' => 'N',
            'BIZPROC' => 'N',
            'LIST_PAGE_URL' => '',
            'DETAIL_PAGE_URL' => $detailUrl,
            'SECTION_PAGE_URL' => '',
        ]);
        if (!$id) {
            throw new RuntimeException($code . ' was not created. ' . $iblock->LAST_ERROR);
        }

        return (int) $id;
    }

    /**
     * @param array<string, mixed> $fields
     */
    private function ensureProperty(int $iblockId, array $fields): void
    {
        $found = \CIBlockProperty::GetList([], [
            'IBLOCK_ID' => $iblockId,
            'CODE' => $fields['CODE'],
        ])->Fetch();
        if (is_array($found)) {
            return;
        }

        $fields['IBLOCK_ID'] = $iblockId;
        $property = new \CIBlockProperty();
        $id = $property->Add($fields);
        if (!$id) {
            throw new RuntimeException($fields['CODE'] . ' was not created. ' . $property->LAST_ERROR);
        }
    }

    private function deleteIblock(string $code): void
    {
        $found = \CIBlock::GetList([], [
            'TYPE' => self::TYPE_ID,
            'CODE' => $code,
            'CHECK_PERMISSIONS' => 'N',
        ])->Fetch();
        if (!is_array($found)) {
            return;
        }

        if (!\CIBlock::Delete((int) $found['ID'])) {
            throw new RuntimeException($code . ' was not deleted');
        }
    }

    private function deleteType(): void
    {
        $found = \CIBlockType::GetByID(self::TYPE_ID)->Fetch();
        if (!is_array($found)) {
            return;
        }

        if (!\CIBlockType::Delete(self::TYPE_ID)) {
            throw new RuntimeException('Iblock type was not deleted');
        }
    }

    private function siteId(): string
    {
        if (!defined('SITE_ID') || SITE_ID === '') {
            throw new RuntimeException('SITE_ID is required to install booking iblocks');
        }

        return SITE_ID;
    }

    /**
     * @return array<string, mixed>
     */
    private function stringProperty(string $code, string $name, bool $required, int $sort): array
    {
        return [
            'NAME' => $name,
            'CODE' => $code,
            'PROPERTY_TYPE' => 'S',
            'IS_REQUIRED' => $required ? 'Y' : 'N',
            'MULTIPLE' => 'N',
            'SORT' => $sort,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function numberProperty(string $code, string $name, bool $required, int $sort): array
    {
        return [
            'NAME' => $name,
            'CODE' => $code,
            'PROPERTY_TYPE' => 'N',
            'IS_REQUIRED' => $required ? 'Y' : 'N',
            'MULTIPLE' => 'N',
            'SORT' => $sort,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function directoryProperty(
        string $code,
        string $name,
        string $table,
        bool $required,
        bool $multiple,
        int $sort
    ): array {
        return [
            'NAME' => $name,
            'CODE' => $code,
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'directory',
            'USER_TYPE_SETTINGS' => ['TABLE_NAME' => $table],
            'IS_REQUIRED' => $required ? 'Y' : 'N',
            'MULTIPLE' => $multiple ? 'Y' : 'N',
            'FILTRABLE' => 'Y',
            'SORT' => $sort,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function userProperty(string $code, string $name, bool $required, bool $multiple, int $sort): array
    {
        return [
            'NAME' => $name,
            'CODE' => $code,
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'UserID',
            'IS_REQUIRED' => $required ? 'Y' : 'N',
            'MULTIPLE' => $multiple ? 'Y' : 'N',
            'FILTRABLE' => 'Y',
            'SORT' => $sort,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function dateTimeProperty(string $code, string $name, bool $required, int $sort): array
    {
        return [
            'NAME' => $name,
            'CODE' => $code,
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'DateTime',
            'IS_REQUIRED' => $required ? 'Y' : 'N',
            'MULTIPLE' => 'N',
            'FILTRABLE' => 'Y',
            'SORT' => $sort,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function dateProperty(string $code, string $name, int $sort): array
    {
        return [
            'NAME' => $name,
            'CODE' => $code,
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'Date',
            'IS_REQUIRED' => 'N',
            'MULTIPLE' => 'N',
            'SORT' => $sort,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function elementProperty(
        string $code,
        string $name,
        int $linkIblockId,
        bool $required,
        bool $multiple,
        int $sort
    ): array {
        return [
            'NAME' => $name,
            'CODE' => $code,
            'PROPERTY_TYPE' => 'E',
            'LINK_IBLOCK_ID' => $linkIblockId,
            'IS_REQUIRED' => $required ? 'Y' : 'N',
            'MULTIPLE' => $multiple ? 'Y' : 'N',
            'FILTRABLE' => 'Y',
            'SORT' => $sort,
        ];
    }

    /**
     * @param list<array<string, mixed>> $values
     * @return array<string, mixed>
     */
    private function listProperty(string $code, string $name, bool $required, int $sort, array $values): array
    {
        return [
            'NAME' => $name,
            'CODE' => $code,
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'L',
            'IS_REQUIRED' => $required ? 'Y' : 'N',
            'MULTIPLE' => 'N',
            'FILTRABLE' => 'Y',
            'SORT' => $sort,
            'VALUES' => $values,
        ];
    }
}
