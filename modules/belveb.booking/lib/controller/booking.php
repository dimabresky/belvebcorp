<?php

namespace Belveb\Booking\Controller;

use Belveb\Booking\Domain\AcceptedDraft;
use Belveb\Booking\Domain\RejectedDraft;
use Belveb\Booking\Service\BookingDesk;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Error;
use InvalidArgumentException;

/**
 * Create, change and cancel bookings. Checks run in the domain, not in the template.
 *
 * This file stays on a lowercase path: Bitrix resolves belveb:booking.booking.add to lib/controller/booking.php.
 */
class Booking extends Controller
{
    /**
     * @param list<int|string> $participants
     * @return array<string, mixed>|null
     */
    public function addAction(
        string $room = '',
        string $date = '',
        string $timeFrom = '',
        string $timeTo = '',
        string $title = '',
        string $description = '',
        array $participants = [],
        string $repeat = 'none',
    ): ?array {
        return $this->save(0, $room, $date, $timeFrom, $timeTo, $title, $description, $participants, $repeat);
    }

    /**
     * @param list<int|string> $participants
     * @return array<string, mixed>|null
     */
    public function updateAction(
        int $originId = 0,
        string $room = '',
        string $date = '',
        string $timeFrom = '',
        string $timeTo = '',
        string $title = '',
        string $description = '',
        array $participants = [],
        string $repeat = 'none',
    ): ?array {
        if (BookingDesk::open()->booking($originId) === null) {
            $this->addError(new Error('Бронь не найдена', 'ACCESS_DENIED'));

            return null;
        }

        return $this->save($originId, $room, $date, $timeFrom, $timeTo, $title, $description, $participants, $repeat);
    }

    /**
     * @return array{id: int, status: string}|null
     */
    public function cancelAction(int $id = 0): ?array
    {
        $desk = BookingDesk::open();
        $booking = $desk->booking($id);
        $viewer = $desk->catalog()->viewer();
        if ($booking === null || !$booking->canCancel($viewer) || $booking->isPast($desk->catalog()->clock())) {
            $this->addError(new Error('Отменить эту встречу нельзя', 'ACCESS_DENIED'));

            return null;
        }

        return ['id' => $id, 'status' => 'cancelled'];
    }

    /**
     * Active bookings of the fixture viewer.
     *
     * @return array{bookings: list<array<string, mixed>>}
     */
    public function myAction(): array
    {
        return ['bookings' => BookingDesk::open()->pages()->my(1)['activeBookings']];
    }

    /**
     * One page of completed and cancelled bookings.
     *
     * @return array{bookings: list<array<string, mixed>>, nav: array{page: int, pages: int}}
     */
    public function previousAction(int $page = 1): array
    {
        $state = BookingDesk::open()->pages()->my($page);

        return [
            'bookings' => $state['previousPage'],
            'nav' => $state['nav'],
        ];
    }

    /**
     * Nearest free range of the requested length.
     *
     * @return array<string, mixed>|null
     */
    public function nearestFreeAction(string $code = '', string $date = '', int $duration = 0, int $ignoreId = 0): ?array
    {
        try {
            $offer = BookingDesk::open()->nearestFree($code, $date, $duration, $ignoreId);
        } catch (InvalidArgumentException $exception) {
            $this->addError(new Error($exception->getMessage(), 'ACCESS_DENIED'));

            return null;
        }

        if ($offer === null) {
            $this->addError(new Error('Переговорная не найдена', 'ACCESS_DENIED'));

            return null;
        }

        return $offer;
    }

    /**
     * @param list<int|string> $participants
     * @return array<string, mixed>|null
     */
    private function save(
        int $originId,
        string $room,
        string $date,
        string $timeFrom,
        string $timeTo,
        string $title,
        string $description,
        array $participants,
        string $repeat,
    ): ?array {
        try {
            $result = BookingDesk::open()->confirm(
                $room,
                $date,
                $timeFrom,
                $timeTo,
                $title,
                $description,
                $participants,
                $repeat,
                $originId,
            );
        } catch (InvalidArgumentException $exception) {
            $this->addError(new Error($exception->getMessage(), 'ACCESS_DENIED'));

            return null;
        }

        if ($result instanceof RejectedDraft) {
            $this->addError(new Error($result->view()['message'], $result->code()));

            return null;
        }

        if (!$result instanceof AcceptedDraft) {
            $this->addError(new Error('Не удалось сохранить бронь', 'ACCESS_DENIED'));

            return null;
        }

        $desk = BookingDesk::open();
        $booking = $result->booking();

        return [
            'message' => $result->view()['message'],
            'booking' => $booking->view($desk->catalog()->viewer(), $desk->catalog()->clock()),
            'timeline' => $desk->timeline($booking->room()->code(), $booking->date()->text(), 0),
        ];
    }
}
