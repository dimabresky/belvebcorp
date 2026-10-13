<?php

namespace Belveb\Booking\Domain;

final class Booking
{
    public function __construct(
        private int $id,
        private Room $room,
        private Person $organizer,
        private CalendarDate $date,
        private TimeRange $range,
        private string $title,
        private string $description,
        private ParticipantList $participants,
        private string $status,
        private SeriesRule $series,
        private int $sourceId,
    ) {
        if ($this->id < 1 || $this->title === '') {
            throw new \InvalidArgumentException('Booking id and title are required');
        }
        $known = ['booked', 'completed', 'cancelled'];
        if (!in_array($this->status, $known, true)) {
            throw new \InvalidArgumentException('Unknown booking status');
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function room(): Room
    {
        return $this->room;
    }

    public function date(): CalendarDate
    {
        return $this->date;
    }

    public function range(): TimeRange
    {
        return $this->range;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function organizer(): Person
    {
        return $this->organizer;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function participants(): ParticipantList
    {
        return $this->participants;
    }

    public function series(): SeriesRule
    {
        return $this->series;
    }

    public function sourceId(): int
    {
        return $this->sourceId;
    }

    public function occupies(): bool
    {
        return $this->status === 'booked';
    }

    public function canCancel(Person $person): bool
    {
        return $this->status === 'booked' && $this->organizer->same($person);
    }

    public function isPast(Clock $clock): bool
    {
        if ($this->date->compare($clock->date()) < 0) {
            return true;
        }
        if ($this->date->compare($clock->date()) > 0) {
            return false;
        }

        return $this->range->end()->total() <= $clock->time()->total();
    }

    public function on(Room $room, CalendarDate $date): bool
    {
        return $this->room->code() === $room->code() && $this->date->compare($date) === 0;
    }

    /**
     * @return array<string, mixed>
     */
    public function view(Person $viewer, Clock $clock): array
    {
        return [
            'id' => $this->id,
            'room' => $this->room->view(),
            'status' => $this->status,
            'participantsCount' => $this->participants->count(),
            'participantsLabel' => $this->participants->countLabel(),
            'timeLabel' => $this->range->label(),
            'timeFrom' => $this->range->start()->text(),
            'timeTo' => $this->range->end()->text(),
            'date' => $this->date->text(),
            'dateLabel' => $this->date->text(),
            'organizer' => $this->organizer->view(),
            'title' => $this->title,
            'description' => $this->description,
            'repeatLabel' => $this->series->label(),
            'repeat' => $this->series->view(),
            'participants' => $this->participants->view(),
            'canCancel' => $this->canCancel($viewer) && !$this->isPast($clock),
            'sourceId' => $this->sourceId,
        ];
    }
}
