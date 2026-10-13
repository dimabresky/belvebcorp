<?php

namespace Belveb\Booking\Domain;

final class Timeline
{
    /**
     * @param list<Booking> $bookings
     */
    public function __construct(
        private Grid $grid,
        private array $bookings,
        private Person $viewer,
        private CalendarDate $day,
        private Clock $clock,
        private int $ignoreId = 0,
    ) {
    }

    public function day(): CalendarDate
    {
        return $this->day;
    }

    public function grid(): Grid
    {
        return $this->grid;
    }

    public function ignore(int $bookingId): self
    {
        return new self($this->grid, $this->bookings, $this->viewer, $this->day, $this->clock, $bookingId);
    }

    /**
     * @return list<Slot>
     */
    public function slots(): array
    {
        $slots = [];
        foreach ($this->grid->cells() as $cell) {
            $slots[] = $this->slotFor($cell);
        }

        return $slots;
    }

    /**
     * @return list<Slot>
     */
    public function painted(TimeRange $selection): array
    {
        $aligned = $this->grid->contains($selection) ? $this->grid->align($selection) : $selection;
        $slots = [];
        foreach ($this->slots() as $slot) {
            $slots[] = $slot->paint($aligned);
        }

        return $slots;
    }

    public function accepts(TimeRange $range): bool
    {
        return $this->conflict($range) instanceof ClearRange;
    }

    public function conflict(TimeRange $range): RejectedDraft|ClearRange
    {
        if ($this->isPast($range)) {
            return new RejectedDraft('PAST_TIME', 'Нельзя бронировать прошедшее время');
        }
        if (!$this->grid->contains($range)) {
            return new RejectedDraft('TIME_BUSY', 'Время занято. Выберите другой слот');
        }
        foreach ($this->active() as $booking) {
            if ($booking->range()->overlaps($range)) {
                return new RejectedDraft('TIME_BUSY', 'Время занято. Выберите другой слот');
            }
        }

        return new ClearRange();
    }

    public function nearestFree(int $duration): NearestFree|NoFreeSlot
    {
        if ($duration < 1) {
            return new NoFreeSlot();
        }

        $cursor = $this->searchStart();
        $limit = $this->grid->to();
        while ($cursor->total() + $duration <= $limit->total()) {
            try {
                $range = new TimeRange($cursor, $cursor->plus($duration));
            } catch (\InvalidArgumentException) {
                break;
            }
            if ($this->accepts($range)) {
                return new NearestFree($range);
            }
            $cursor = $cursor->plus($this->grid->stepMinutes());
        }

        return new NoFreeSlot();
    }

    /**
     * @return list<Booking>
     */
    public function dayBookings(): array
    {
        $rows = [];
        foreach ($this->bookings as $booking) {
            if ($booking->date()->compare($this->day) === 0) {
                $rows[] = $booking;
            }
        }

        return $rows;
    }

    /**
     * @return array<string, mixed>
     */
    public function view(): array
    {
        $slots = [];
        foreach ($this->slots() as $slot) {
            $slots[] = $slot->view();
        }

        return [
            'updatedAt' => $this->clock->time()->text(),
            'now' => $this->clock->date()->compare($this->day) === 0 ? $this->clock->time()->text() : '',
            'slots' => $slots,
        ];
    }

    private function slotFor(TimeRange $cell): Slot
    {
        foreach ($this->active() as $booking) {
            if (!$booking->range()->overlaps($cell)) {
                continue;
            }
            $state = $booking->organizer()->same($this->viewer) ? 'mine' : 'busy';

            return new Slot($cell, $state, $booking->title(), $booking->organizer()->name());
        }

        if ($this->clock->date()->compare($this->day) === 0 && $cell->covers($this->clock->time())) {
            return new Slot($cell, 'now');
        }

        return new Slot($cell, 'free');
    }

    /**
     * @return list<Booking>
     */
    private function active(): array
    {
        $rows = [];
        foreach ($this->bookings as $booking) {
            if ($booking->id() === $this->ignoreId || !$booking->occupies()) {
                continue;
            }
            if ($booking->date()->compare($this->day) !== 0) {
                continue;
            }
            $rows[] = $booking;
        }

        return $rows;
    }

    private function isPast(TimeRange $range): bool
    {
        if ($this->day->compare($this->clock->date()) < 0) {
            return true;
        }
        if ($this->day->compare($this->clock->date()) > 0) {
            return false;
        }

        return $range->start()->total() < $this->clock->time()->total();
    }

    private function searchStart(): MinuteOfDay
    {
        $from = $this->grid->from();
        if ($this->clock->date()->compare($this->day) !== 0) {
            return $from;
        }
        $now = $this->clock->time()->ceilTo($this->grid->stepMinutes());
        if ($now->total() < $from->total()) {
            return $from;
        }

        return $now;
    }
}
