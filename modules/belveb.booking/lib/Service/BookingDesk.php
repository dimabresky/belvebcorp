<?php

namespace Belveb\Booking\Service;

use Belveb\Booking\Domain\AcceptedDraft;
use Belveb\Booking\Domain\Booking;
use Belveb\Booking\Domain\BookingDraft;
use Belveb\Booking\Domain\CalendarDate;
use Belveb\Booking\Domain\MinuteOfDay;
use Belveb\Booking\Domain\NearestFree;
use Belveb\Booking\Domain\NoBooking;
use Belveb\Booking\Domain\ParticipantList;
use Belveb\Booking\Domain\Person;
use Belveb\Booking\Domain\RejectedDraft;
use Belveb\Booking\Domain\RoomFilter;
use Belveb\Booking\Domain\SeriesRule;
use Belveb\Booking\Domain\TimeRange;
use Belveb\Booking\Fixture\FixtureCatalog;
use Belveb\Booking\View\PageState;
use Belveb\Booking\View\ScreenData;
use InvalidArgumentException;

/**
 * Reads the fixture catalog and runs domain checks for page and action controllers.
 */
final class BookingDesk
{
    public function __construct(private FixtureCatalog $catalog)
    {
    }

    public static function open(): self
    {
        return new self(ScreenData::open()->catalog());
    }

    public function pages(): PageState
    {
        return new PageState($this->catalog);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function listRooms(RoomFilter $filter): array
    {
        $rooms = [];
        foreach ($this->catalog->rooms() as $room) {
            $timeline = $this->catalog->timeline($room, $filter->date());
            if (!$filter->matches($room, $timeline)) {
                continue;
            }
            $view = $room->view();
            $view['IS_AVAILABLE'] = true;
            $rooms[] = $view;
        }

        return $rooms;
    }

    public function filter(
        string $date,
        string $timeFrom,
        string $timeTo,
        int $participants,
        string $floor,
        string $type,
    ): RoomFilter {
        $defaults = $this->catalog->defaultFilter();
        $day = $date !== '' ? new CalendarDate($date) : $defaults->date();
        $from = $timeFrom !== '' ? new MinuteOfDay($timeFrom) : $defaults->range()->start();
        $to = $timeTo !== '' ? new MinuteOfDay($timeTo) : $defaults->range()->end();
        $count = $participants > 0 ? $participants : $defaults->participants();

        return new RoomFilter($day, new TimeRange($from, $to), $count, $floor, $type);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function room(string $code): ?array
    {
        if (!$this->catalog->hasRoom($code)) {
            return null;
        }

        return $this->catalog->room($code)->view();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function timeline(string $code, string $date, int $ignoreId = 0): ?array
    {
        if (!$this->catalog->hasRoom($code)) {
            return null;
        }

        $room = $this->catalog->room($code);
        $day = $date !== '' ? new CalendarDate($date) : $this->catalog->clock()->date();

        return $this->catalog->timeline($room, $day, $ignoreId)->view();
    }

    /**
     * @return array{found: bool, timeFrom?: string, timeTo?: string, timeLabel?: string}
     */
    public function nearestFree(string $code, string $date, int $duration, int $ignoreId = 0): ?array
    {
        if (!$this->catalog->hasRoom($code)) {
            return null;
        }

        $room = $this->catalog->room($code);
        $day = $date !== '' ? new CalendarDate($date) : $this->catalog->clock()->date();
        $offer = $this->catalog->timeline($room, $day, $ignoreId)->nearestFree($duration);
        if (!$offer instanceof NearestFree) {
            return ['found' => false];
        }

        return [
            'found' => true,
            'timeFrom' => $offer->range()->start()->text(),
            'timeTo' => $offer->range()->end()->text(),
            'timeLabel' => $offer->range()->label(),
        ];
    }

    /**
     * @param list<int|string> $participantIds
     */
    public function confirm(
        string $roomCode,
        string $date,
        string $timeFrom,
        string $timeTo,
        string $title,
        string $description,
        array $participantIds,
        string $repeat,
        int $originId = 0,
    ): AcceptedDraft|RejectedDraft {
        if (!$this->catalog->hasRoom($roomCode)) {
            return new RejectedDraft('ACCESS_DENIED', 'Переговорная не найдена');
        }

        $room = $this->catalog->room($roomCode);
        $day = new CalendarDate($date);
        $origin = $this->booking($originId);
        $ignoreId = $origin instanceof Booking ? $origin->id() : 0;
        $timeline = $this->catalog->timeline($room, $day, $ignoreId);
        $people = [];
        foreach ($participantIds as $participantId) {
            $person = $this->person((int) $participantId);
            if (!$person instanceof Person) {
                return new RejectedDraft('ACCESS_DENIED', 'Участник не найден');
            }
            $people[] = $person;
        }
        if ($people === []) {
            $people[] = $this->catalog->viewer();
        }

        $draft = new BookingDraft(
            $room,
            $this->catalog->viewer(),
            $day,
            new TimeRange(new MinuteOfDay($timeFrom), new MinuteOfDay($timeTo)),
            $title,
            $description,
            new ParticipantList($people),
            new SeriesRule($repeat !== '' ? $repeat : 'none', $day, 90),
            $timeline,
            $this->catalog->clock(),
            $origin ?? new NoBooking(),
        );

        return $draft->confirm();
    }

    public function booking(int $id): ?Booking
    {
        if ($id < 1) {
            return null;
        }

        foreach ($this->catalog->bookings() as $booking) {
            if ($booking->id() === $id) {
                return $booking;
            }
        }

        return null;
    }

    /**
     * @return list<array{id: int, name: string}>
     */
    public function searchPeople(string $query): array
    {
        $needle = mb_strtolower(trim($query));
        $rows = [];
        foreach ($this->catalog->people() as $person) {
            if ($needle !== '' && !str_contains(mb_strtolower($person->name()), $needle)) {
                continue;
            }
            $rows[] = $person->view();
        }

        return $rows;
    }

    public function catalog(): FixtureCatalog
    {
        return $this->catalog;
    }

    private function person(int $id): ?Person
    {
        try {
            return $this->catalog->person($id);
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}
