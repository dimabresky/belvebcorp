<?php

namespace Belveb\Booking\Domain;

final class RoomFilter
{
    public function __construct(
        private CalendarDate $date,
        private TimeRange $range,
        private int $participants,
        private string $floor,
        private string $type,
    ) {
        if ($this->participants < 1) {
            throw new \InvalidArgumentException('Participant count must be positive');
        }
    }

    public function date(): CalendarDate
    {
        return $this->date;
    }

    public function range(): TimeRange
    {
        return $this->range;
    }

    public function participants(): int
    {
        return $this->participants;
    }

    public function matches(Room $room, Timeline $timeline): bool
    {
        if ($this->floor !== '' && (string) $room->floor() !== $this->floor) {
            return false;
        }
        if ($this->type !== '' && $room->type() !== $this->type) {
            return false;
        }
        if ($room->capacity() < $this->participants) {
            return false;
        }

        return $room->availableFor($this->range, $timeline);
    }

    public function reset(self $defaults): self
    {
        return $defaults;
    }

    public function withFloor(string $floor): self
    {
        return new self($this->date, $this->range, $this->participants, $floor, $this->type);
    }

    public function withType(string $type): self
    {
        return new self($this->date, $this->range, $this->participants, $this->floor, $type);
    }

    public function withParticipants(int $count): self
    {
        return new self($this->date, $this->range, $count, $this->floor, $this->type);
    }

    public function withRange(TimeRange $range): self
    {
        return new self($this->date, $range, $this->participants, $this->floor, $this->type);
    }

    public function withDate(CalendarDate $date): self
    {
        return new self($date, $this->range, $this->participants, $this->floor, $this->type);
    }

    /**
     * @return array{date: string, timeFrom: string, timeTo: string, timeLabel: string, participants: int, floor: string, type: string}
     */
    public function view(): array
    {
        return [
            'date' => $this->date->text(),
            'timeFrom' => $this->range->start()->text(),
            'timeTo' => $this->range->end()->text(),
            'timeLabel' => $this->range->label(),
            'participants' => $this->participants,
            'floor' => $this->floor,
            'type' => $this->type,
        ];
    }
}
