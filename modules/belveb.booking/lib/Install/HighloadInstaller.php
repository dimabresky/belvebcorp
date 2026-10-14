<?php

namespace Belveb\Booking\Install;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use RuntimeException;

/**
 * Creates the room-type and amenity dictionaries and removes them on uninstall.
 */
final class HighloadInstaller
{
    public function install(): void
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new RuntimeException('Module highloadblock is required');
        }

        $types = $this->block('BelvebBookingRoomType', 'belveb_booking_room_type', [
            $this->stringField('UF_NAME', 'Название', true),
            $this->stringField('UF_XML_ID', 'Внешний код', true),
            $this->integerField('UF_SORT', 'Сортировка', 500),
        ]);
        $this->records($types, [
            ['UF_NAME' => 'Зал Правления', 'UF_XML_ID' => 'board', 'UF_SORT' => 100],
            ['UF_NAME' => 'Переговорная', 'UF_XML_ID' => 'meeting', 'UF_SORT' => 200],
        ]);

        $amenities = $this->block('BelvebBookingAmenity', 'belveb_booking_amenity', [
            $this->stringField('UF_NAME', 'Название', true),
            $this->stringField('UF_XML_ID', 'Внешний код', true),
            $this->fileField('UF_ICON', 'Иконка'),
            $this->integerField('UF_SORT', 'Сортировка', 500),
        ]);
        $this->records($amenities, [
            ['UF_NAME' => 'Wifi', 'UF_XML_ID' => 'wifi', 'UF_SORT' => 100],
            ['UF_NAME' => 'Система видеоконференцсвязи', 'UF_XML_ID' => 'vcs', 'UF_SORT' => 200],
            ['UF_NAME' => 'Проектор', 'UF_XML_ID' => 'projector', 'UF_SORT' => 300],
            ['UF_NAME' => 'Экран', 'UF_XML_ID' => 'screen', 'UF_SORT' => 400],
            ['UF_NAME' => 'USB-C', 'UF_XML_ID' => 'usb_c', 'UF_SORT' => 500],
        ]);
    }

    public function uninstall(bool $keepData): void
    {
        if ($keepData || !Loader::includeModule('highloadblock')) {
            return;
        }

        $this->delete('BelvebBookingAmenity');
        $this->delete('BelvebBookingRoomType');
    }

    /**
     * @param list<array<string, mixed>> $fields
     */
    private function block(string $name, string $table, array $fields): int
    {
        $existing = HighloadBlockTable::getList([
            'filter' => ['=NAME' => $name],
            'limit' => 1,
        ])->fetch();

        if (is_array($existing)) {
            $id = (int) $existing['ID'];
        } else {
            $result = HighloadBlockTable::add([
                'NAME' => $name,
                'TABLE_NAME' => $table,
            ]);
            if (!$result->isSuccess()) {
                throw new RuntimeException($name . ': ' . implode(', ', $result->getErrorMessages()));
            }
            $id = (int) $result->getId();
        }

        foreach ($fields as $field) {
            $this->ensureField($id, $field);
        }

        return $id;
    }

    /**
     * @param array<string, mixed> $field
     */
    private function ensureField(int $blockId, array $field): void
    {
        $entityId = 'HLBLOCK_' . $blockId;
        $userField = new \CUserTypeEntity();
        $found = $userField->GetList([], [
            'ENTITY_ID' => $entityId,
            'FIELD_NAME' => $field['FIELD_NAME'],
        ])->Fetch();
        if (is_array($found)) {
            return;
        }

        $field['ENTITY_ID'] = $entityId;
        $added = $userField->Add($field);
        if (!$added) {
            global $APPLICATION;
            $exception = is_object($APPLICATION) ? $APPLICATION->GetException() : null;
            $error = is_object($exception) ? $exception->GetString() : '';
            throw new RuntimeException($field['FIELD_NAME'] . ' was not created. ' . $error);
        }
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function records(int $blockId, array $rows): void
    {
        $block = HighloadBlockTable::getById($blockId)->fetch();
        if (!is_array($block)) {
            throw new RuntimeException('Highload block ' . $blockId . ' was not found');
        }

        $dataClass = HighloadBlockTable::compileEntity($block)->getDataClass();
        foreach ($rows as $row) {
            $exists = $dataClass::getList([
                'filter' => ['=UF_XML_ID' => $row['UF_XML_ID']],
                'limit' => 1,
            ])->fetch();
            if (is_array($exists)) {
                continue;
            }

            $result = $dataClass::add($row);
            if (!$result->isSuccess()) {
                throw new RuntimeException($row['UF_XML_ID'] . ': ' . implode(', ', $result->getErrorMessages()));
            }
        }
    }

    private function delete(string $name): void
    {
        $existing = HighloadBlockTable::getList([
            'filter' => ['=NAME' => $name],
            'limit' => 1,
        ])->fetch();
        if (!is_array($existing)) {
            return;
        }

        $result = HighloadBlockTable::delete((int) $existing['ID']);
        if (!$result->isSuccess()) {
            throw new RuntimeException($name . ': ' . implode(', ', $result->getErrorMessages()));
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function stringField(string $name, string $label, bool $required): array
    {
        return $this->field($name, $label, 'string', $required, [
            'SIZE' => 50,
            'ROWS' => 1,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function integerField(string $name, string $label, int $default): array
    {
        return $this->field($name, $label, 'integer', false, [
            'DEFAULT_VALUE' => $default,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function fileField(string $name, string $label): array
    {
        return $this->field($name, $label, 'file', false, [
            'EXTENSIONS' => ['svg' => true],
        ]);
    }

    /**
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    private function field(string $name, string $label, string $type, bool $required, array $settings): array
    {
        $labels = ['ru' => $label, 'en' => $label];

        return [
            'FIELD_NAME' => $name,
            'USER_TYPE_ID' => $type,
            'XML_ID' => $name,
            'SORT' => 100,
            'MULTIPLE' => 'N',
            'MANDATORY' => $required ? 'Y' : 'N',
            'SHOW_FILTER' => 'I',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' => $settings,
            'EDIT_FORM_LABEL' => $labels,
            'LIST_COLUMN_LABEL' => $labels,
            'LIST_FILTER_LABEL' => $labels,
        ];
    }
}
