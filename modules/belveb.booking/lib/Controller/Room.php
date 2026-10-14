<?php

namespace Belveb\Booking\Controller;

use Belveb\Booking\Service\BookingDesk;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Error;
use InvalidArgumentException;

/**
 * Room list, card and day timeline for the booking screens.
 */
class Room extends Controller
{
    /**
     * Rooms that match the filter, each marked available.
     *
     * @return array{rooms: list<array<string, mixed>>}|null
     */
    public function listAction(
        string $date = '',
        string $timeFrom = '',
        string $timeTo = '',
        int $participants = 0,
        string $floor = '',
        string $type = '',
    ): ?array {
        try {
            $filter = BookingDesk::open()->filter($date, $timeFrom, $timeTo, $participants, $floor, $type);
        } catch (InvalidArgumentException $exception) {
            $this->addError(new Error($exception->getMessage(), 'ACCESS_DENIED'));

            return null;
        }

        return ['rooms' => BookingDesk::open()->listRooms($filter)];
    }

    /**
     * One room by symbolic code.
     *
     * @return array<string, mixed>|null
     */
    public function getAction(string $code = ''): ?array
    {
        $room = BookingDesk::open()->room($code);
        if ($room === null) {
            $this->addError(new Error('Переговорная не найдена', 'ACCESS_DENIED'));

            return null;
        }

        return $room;
    }

    /**
     * Occupied slots of a room on one day.
     *
     * @return array<string, mixed>|null
     */
    public function timelineAction(string $code = '', string $date = '', int $ignoreId = 0): ?array
    {
        try {
            $timeline = BookingDesk::open()->timeline($code, $date, $ignoreId);
        } catch (InvalidArgumentException $exception) {
            $this->addError(new Error($exception->getMessage(), 'ACCESS_DENIED'));

            return null;
        }

        if ($timeline === null) {
            $this->addError(new Error('Переговорная не найдена', 'ACCESS_DENIED'));

            return null;
        }

        return $timeline;
    }
}
