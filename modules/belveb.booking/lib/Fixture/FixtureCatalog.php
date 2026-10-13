<?php

namespace Belveb\Booking\Fixture;

use Belveb\Booking\Domain\Amenity;
use Belveb\Booking\Domain\Booking;
use Belveb\Booking\Domain\CalendarDate;
use Belveb\Booking\Domain\Clock;
use Belveb\Booking\Domain\FixedClock;
use Belveb\Booking\Domain\Grid;
use Belveb\Booking\Domain\MinuteOfDay;
use Belveb\Booking\Domain\ParticipantList;
use Belveb\Booking\Domain\Person;
use Belveb\Booking\Domain\Room;
use Belveb\Booking\Domain\RoomFilter;
use Belveb\Booking\Domain\SeriesRule;
use Belveb\Booking\Domain\TimeRange;
use Belveb\Booking\Domain\Timeline;

final class FixtureCatalog
{
    private Clock $clock;

    private Person $viewer;

    /** @var list<Person> */
    private array $people;

    /** @var list<Room> */
    private array $rooms;

    /** @var list<Booking> */
    private array $bookings;

    public function __construct(private string $imageBase)
    {
        $this->clock = new FixedClock(new CalendarDate('21.09.2026'), new MinuteOfDay('10:00'));
        $this->viewer = new Person(1, 'Евгений Меркулов');
        $this->people = [
            $this->viewer,
            new Person(2, 'Ковалёв А.'),
            new Person(3, 'Иванова М.'),
            new Person(4, 'Петров С.'),
            new Person(5, 'Сидорова Е.'),
            new Person(6, 'Орлов Д.'),
        ];
        $this->rooms = $this->buildRooms();
        $this->bookings = $this->buildBookings();
    }

    public function clock(): Clock
    {
        return $this->clock;
    }

    public function viewer(): Person
    {
        return $this->viewer;
    }

    /**
     * @return list<Person>
     */
    public function people(): array
    {
        return $this->people;
    }

    /**
     * @return list<Room>
     */
    public function rooms(): array
    {
        return $this->rooms;
    }

    /**
     * @return list<Booking>
     */
    public function bookings(): array
    {
        return $this->bookings;
    }

    public function room(string $code): Room
    {
        foreach ($this->rooms as $room) {
            if ($room->code() === $code) {
                return $room;
            }
        }

        throw new \InvalidArgumentException('Unknown room');
    }

    public function hasRoom(string $code): bool
    {
        foreach ($this->rooms as $room) {
            if ($room->code() === $code) {
                return true;
            }
        }

        return false;
    }

    public function defaultFilter(): RoomFilter
    {
        return new RoomFilter(
            new CalendarDate('21.09.2026'),
            new TimeRange(new MinuteOfDay('09:00'), new MinuteOfDay('11:00')),
            6,
            '',
            '',
        );
    }

    public function timeline(Room $room, CalendarDate $day, int $ignoreId = 0): Timeline
    {
        $rows = [];
        foreach ($this->bookings as $booking) {
            if ($booking->room()->code() === $room->code()) {
                $rows[] = $booking;
            }
        }

        return new Timeline($room->grid(), $rows, $this->viewer, $day, $this->clock, $ignoreId);
    }

    public function person(int $id): Person
    {
        foreach ($this->people as $person) {
            if ($person->id() === $id) {
                return $person;
            }
        }

        throw new \InvalidArgumentException('Unknown person');
    }

    /**
     * @return list<Room>
     */
    private function buildRooms(): array
    {
        $amenities = $this->amenities();
        $grid = new Grid(new MinuteOfDay('08:00'), new MinuteOfDay('20:00'), 10);

        return [
            $this->roomRow('bolshoy-zal-pravleniya', 'Переговорная 1', 'Большой зал Правления', 'room-1.jpg', 10, 6, 'board', $amenities, $grid),
            $this->roomRow('malyy-zal-pravleniya', 'Переговорная 2', 'Малый зал Правления', 'room-2.jpg', 4, 6, 'board', $amenities, $grid),
            $this->roomRow('bolshoy-zal', 'Переговорная 3', 'Большой зал', 'room-3.jpg', 10, 5, 'meeting', $amenities, $grid),
            $this->roomRow('malyy-zal', 'Переговорная 4', 'Малый зал', 'room-4.jpg', 4, 5, 'meeting', $amenities, $grid),
        ];
    }

    /**
     * @param list<Amenity> $amenities
     */
    private function roomRow(
        string $code,
        string $label,
        string $title,
        string $image,
        int $capacity,
        int $floor,
        string $type,
        array $amenities,
        Grid $grid,
    ): Room {
        return new Room(
            $code,
            $label,
            $title,
            $this->imageBase . '/' . $image,
            $capacity,
            $floor,
            $type,
            $amenities,
            $grid,
            '/booking/' . $code . '/',
        );
    }

    /**
     * @return list<Amenity>
     */
    private function amenities(): array
    {
        $rows = [
            ['Wifi', 'icon-wifi.svg', '18.6667', '13.6669'],
            ['Система видеоконференцсвязи', 'icon-video.svg', '17.125', '10.875'],
            ['Проектор', 'icon-projector.svg', '21.5', '16.5'],
            ['Экран', 'icon-screen.svg', '20', '20'],
            ['USB-C', 'icon-usb.svg', '22', '10'],
        ];
        $amenities = [];
        foreach ($rows as $row) {
            $amenities[] = new Amenity($row[0], $this->imageBase . '/' . $row[1], $row[2], $row[3]);
        }

        return $amenities;
    }

    /**
     * @return list<Booking>
     */
    private function buildBookings(): array
    {
        $none = new SeriesRule('none', new CalendarDate('21.09.2026'), 90);
        $daily = new SeriesRule('daily', new CalendarDate('04.09.2026'), 90);
        $room1 = $this->room('bolshoy-zal-pravleniya');
        $room2 = $this->room('malyy-zal-pravleniya');
        $room3 = $this->room('bolshoy-zal');
        $room4 = $this->room('malyy-zal');
        $me = $this->viewer;
        $kovalev = $this->person(2);

        return [
            $this->booking(1, $room1, $me, '21.09.2026', '14:00', '15:30', 'Планёрка правления', 'Итоги недели и план на следующую.', [1, 2, 3, 4, 5, 6], 'booked', $none, 0),
            $this->booking(2, $room1, $kovalev, '21.09.2026', '11:00', '12:40', 'Комитет по рискам', 'Обсуждение лимитов.', [2, 3, 4], 'booked', $none, 0),
            $this->booking(3, $room2, $me, '21.09.2026', '16:00', '17:30', 'Встреча с командой', 'Разбор задач спринта.', [1, 3, 5], 'booked', $none, 0),
            $this->booking(4, $room3, $kovalev, '22.09.2026', '11:00', '12:00', 'Согласование бюджета', '', [2, 1, 3], 'booked', $none, 0),
            $this->booking(5, $room1, $me, '06.09.2026', '10:00', '11:00', 'Ежедневный стендап', 'Короткий статус по задачам.', [1, 3, 4], 'completed', $daily, 0),
            $this->booking(6, $room2, $me, '10.09.2026', '15:00', '16:00', 'Отменённая встреча', 'Перенесена заказчиком.', [1, 2], 'cancelled', $none, 0),
            $this->booking(7, $room4, $kovalev, '12.09.2026', '09:30', '10:30', 'Интервью', '', [2, 1], 'completed', $none, 0),
            $this->booking(8, $room3, $me, '01.09.2026', '13:00', '14:00', 'Ретроспектива', 'Что улучшить в процессе.', [1, 5, 6], 'completed', $none, 0),
            $this->booking(9, $room1, $me, '02.09.2026', '17:00', '18:00', 'Демо для правления', '', [1, 2, 6], 'cancelled', $none, 0),
        ];
    }

    /**
     * @param list<int> $participantIds
     */
    private function booking(
        int $id,
        Room $room,
        Person $organizer,
        string $date,
        string $from,
        string $to,
        string $title,
        string $description,
        array $participantIds,
        string $status,
        SeriesRule $series,
        int $sourceId,
    ): Booking {
        $people = [];
        foreach ($participantIds as $participantId) {
            $people[] = $this->person($participantId);
        }

        return new Booking(
            $id,
            $room,
            $organizer,
            new CalendarDate($date),
            new TimeRange(new MinuteOfDay($from), new MinuteOfDay($to)),
            $title,
            $description,
            new ParticipantList($people),
            $status,
            $series,
            $sourceId,
        );
    }
}
