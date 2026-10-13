<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

class BelvebBookingRooms extends CBitrixComponent
{
    public function executeComponent(): void
    {
        $amenities = $this->amenities();

        $this->arResult = [
            'FILTER' => [
                'DATE' => '21.09.2026',
                'TIME' => '09:00-11:00',
                'PARTICIPANTS' => '6',
            ],
            'ROOMS' => [
                $this->room('Переговорная 1', 'Большой зал Правления', 'room-1.jpg', '6 этаж', $amenities),
                $this->room('Переговорная 2', 'Малый зал Правления', 'room-2.jpg', '6 этаж', $amenities),
                $this->room('Переговорная 3', 'Большой зал', 'room-3.jpg', '5 этаж', $amenities),
                $this->room('Переговорная 4', 'Малый зал', 'room-4.jpg', '5 этаж', $amenities),
            ],
        ];

        $this->includeComponentTemplate();
    }

    /**
     * @param array<int, array<string, string>> $amenities
     * @return array<string, mixed>
     */
    private function room(
        string $code,
        string $title,
        string $image,
        string $floor,
        array $amenities
    ): array {
        return [
            'CODE' => $code,
            'TITLE' => $title,
            'IMAGE' => $image,
            'CAPACITY' => 'до 10 человек',
            'FLOOR' => $floor,
            'AMENITIES' => $amenities,
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function amenities(): array
    {
        return [
            [
                'LABEL' => 'Wifi',
                'ICON' => 'icon-wifi.svg',
                'WIDTH' => '18.6667',
                'HEIGHT' => '13.6669',
            ],
            [
                'LABEL' => 'Система видеоконференцсвязи',
                'ICON' => 'icon-video.svg',
                'WIDTH' => '17.125',
                'HEIGHT' => '10.875',
            ],
            [
                'LABEL' => 'Проектор',
                'ICON' => 'icon-projector.svg',
                'WIDTH' => '21.5',
                'HEIGHT' => '16.5',
            ],
            [
                'LABEL' => 'Экран',
                'ICON' => 'icon-screen.svg',
                'WIDTH' => '20',
                'HEIGHT' => '20',
            ],
            [
                'LABEL' => 'USB-C',
                'ICON' => 'icon-usb.svg',
                'WIDTH' => '22',
                'HEIGHT' => '10',
            ],
        ];
    }
}
