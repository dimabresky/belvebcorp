<?php

namespace Belveb\Booking\View;

use Belveb\Booking\Domain\Booking;
use Belveb\Booking\Domain\CalendarDate;
use Belveb\Booking\Fixture\FixtureCatalog;

final class PageState
{
    private const PAGE_SIZE = 3;

    public function __construct(private FixtureCatalog $catalog)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function rooms(): array
    {
        return $this->common('rooms');
    }

    /**
     * @return array<string, mixed>
     */
    public function my(int $page): array
    {
        $state = $this->common('my');
        $active = [];
        $previous = [];
        foreach ($this->catalog->bookings() as $booking) {
            if (!$this->involves($booking)) {
                continue;
            }
            $row = $booking->view($this->catalog->viewer(), $this->catalog->clock());
            if ($booking->status() === 'booked' && !$booking->isPast($this->catalog->clock())) {
                $active[] = $row;
                continue;
            }
            if ($booking->status() === 'completed' || $booking->status() === 'cancelled') {
                $previous[] = $row;
            }
        }

        $pages = max(1, (int) ceil(count($previous) / self::PAGE_SIZE));
        $page = min(max($page, 1), $pages);
        $offset = ($page - 1) * self::PAGE_SIZE;

        $state['activeBookings'] = $active;
        $state['previousBookings'] = $previous;
        $state['previousPage'] = array_slice($previous, $offset, self::PAGE_SIZE);
        $state['nav'] = ['page' => $page, 'pages' => $pages];

        return $state;
    }

    /**
     * @return array<string, mixed>
     */
    public function room(string $code): array
    {
        $state = $this->common('room');
        if (!$this->catalog->hasRoom($code)) {
            $state['found'] = false;

            return $state;
        }

        $room = $this->catalog->room($code);
        $day = $this->catalog->clock()->date();
        $timeline = $this->catalog->timeline($room, $day);
        $state['found'] = true;
        $state['room'] = $room->view();
        $state['day'] = $day->text();
        $state['days'] = $this->days($day);
        $state['grid'] = $room->grid()->view();
        $state['timeline'] = $timeline->view();
        $state['dayBookings'] = $this->dayRows($timeline->dayBookings());

        return $state;
    }

    /**
     * @return array<string, mixed>
     */
    private function common(string $page): array
    {
        $rooms = [];
        foreach ($this->catalog->rooms() as $room) {
            $rooms[] = $room->view();
        }
        $bookings = [];
        foreach ($this->catalog->bookings() as $booking) {
            $bookings[] = $booking->view($this->catalog->viewer(), $this->catalog->clock());
        }
        $people = [];
        foreach ($this->catalog->people() as $person) {
            $people[] = $person->view();
        }

        return [
            'page' => $page,
            'urls' => [
                'rooms' => '/booking/',
                'my' => '/booking/my/',
            ],
            'assets' => [
                'calendar' => $this->asset('icon-calendar.svg'),
                'time' => $this->asset('icon-time.svg'),
                'people' => $this->asset('icon-people.svg'),
                'person' => $this->asset('icon-person.svg'),
                'building' => $this->asset('icon-building.svg'),
                'search' => $this->asset('icon-search.svg'),
                'chevron' => $this->asset('icon-chevron.svg'),
                'chevronSm' => $this->asset('icon-chevron-sm.svg'),
            ],
            'filter' => $this->catalog->defaultFilter()->view(),
            'filterOptions' => [
                'floors' => [5, 6],
                'types' => [
                    ['xmlId' => 'board', 'name' => 'Зал Правления'],
                    ['xmlId' => 'meeting', 'name' => 'Переговорная'],
                ],
                'dates' => $this->dateChoices(),
                'participants' => [1, 2, 4, 6, 8, 10],
                'durations' => [30, 60, 90, 120],
            ],
            'rooms' => $rooms,
            'bookings' => $bookings,
            'people' => $people,
            'user' => $this->catalog->viewer()->view(),
            'clock' => [
                'date' => $this->catalog->clock()->date()->text(),
                'time' => $this->catalog->clock()->time()->text(),
            ],
            'activeBookings' => [],
            'previousBookings' => [],
            'previousPage' => [],
            'nav' => ['page' => 1, 'pages' => 1],
            'found' => true,
            'room' => null,
            'day' => $this->catalog->clock()->date()->text(),
            'days' => [],
            'grid' => ['workFrom' => '08:00', 'workTo' => '20:00', 'stepMinutes' => 10],
            'timeline' => ['updatedAt' => '', 'now' => '', 'slots' => []],
            'dayBookings' => [],
        ];
    }

    private function involves(Booking $booking): bool
    {
        $viewer = $this->catalog->viewer();
        if ($booking->organizer()->same($viewer)) {
            return true;
        }

        return $booking->participants()->has($viewer);
    }

    /**
     * @param list<Booking> $bookings
     * @return list<array<string, mixed>>
     */
    private function dayRows(array $bookings): array
    {
        $rows = [];
        foreach ($bookings as $booking) {
            $rows[] = $booking->view($this->catalog->viewer(), $this->catalog->clock());
        }

        return $rows;
    }

    /**
     * @return list<array{date: string, label: string, isToday: bool}>
     */
    private function days(CalendarDate $selected): array
    {
        $names = [1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт', 5 => 'Пт', 6 => 'Сб', 7 => 'Вс'];
        $days = [];
        $start = $this->catalog->clock()->date();
        for ($offset = 0; $offset < 7; $offset++) {
            $date = $start->plusDays($offset);
            $days[] = [
                'date' => $date->text(),
                'label' => $offset === 0 ? 'Сегодня' : $names[$date->weekday()] . ' ' . substr($date->text(), 0, 5),
                'isToday' => $offset === 0,
                'selected' => $date->compare($selected) === 0,
            ];
        }

        return $days;
    }

    /**
     * @return list<string>
     */
    private function dateChoices(): array
    {
        $dates = [];
        $start = $this->catalog->clock()->date();
        for ($offset = 0; $offset < 7; $offset++) {
            $dates[] = $start->plusDays($offset)->text();
        }

        return $dates;
    }

    private function asset(string $file): string
    {
        $room = $this->catalog->rooms()[0];
        $base = dirname($room->view()['image']);

        return $base . '/' . $file;
    }
}
