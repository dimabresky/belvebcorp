<?php

namespace Belveb\Booking\Domain;

final class BookingDraft
{
    public function __construct(
        private Room $room,
        private Person $organizer,
        private CalendarDate $date,
        private TimeRange $range,
        private string $title,
        private string $description,
        private ParticipantList $participants,
        private SeriesRule $series,
        private Timeline $timeline,
        private Clock $clock,
        private Booking|NoBooking $origin,
    ) {
    }

    public function room(): Room
    {
        return $this->room;
    }

    public function range(): TimeRange
    {
        return $this->range;
    }

    public function withTitle(string $title): self
    {
        return $this->copy(title: $title);
    }

    public function withDescription(string $description): self
    {
        return $this->copy(description: $description);
    }

    public function withRange(TimeRange $range): self
    {
        return $this->copy(range: $range);
    }

    public function withDate(CalendarDate $date, Timeline $timeline): self
    {
        return $this->copy(date: $date, timeline: $timeline);
    }

    public function withRoom(Room $room, Timeline $timeline): self
    {
        return $this->copy(room: $room, timeline: $timeline);
    }

    public function withParticipants(ParticipantList $participants): self
    {
        return $this->copy(participants: $participants);
    }

    public function withSeries(SeriesRule $series): self
    {
        return $this->copy(series: $series);
    }

    public function unchanged(): bool
    {
        if (!$this->origin instanceof Booking) {
            return false;
        }

        return $this->origin->title() === $this->title
            && $this->origin->description() === $this->description
            && $this->origin->room()->code() === $this->room->code()
            && $this->origin->date()->compare($this->date) === 0
            && $this->origin->range()->same($this->range)
            && $this->origin->participants()->same($this->participants)
            && $this->origin->series()->same($this->series);
    }

    public function confirm(): AcceptedDraft|RejectedDraft
    {
        $title = trim($this->title);
        if ($title === '') {
            return new RejectedDraft('TITLE_REQUIRED', 'Укажите тему встречи');
        }
        if (!$this->participants->fitsIn($this->room)) {
            return new RejectedDraft('CAPACITY_EXCEEDED', 'Слишком много участников для этой переговорной');
        }

        $conflict = $this->timeline->conflict($this->range);
        if ($conflict instanceof RejectedDraft) {
            return $conflict;
        }

        if (!$this->origin instanceof Booking) {
            return new AcceptedDraft($this->preview($title));
        }

        return new AcceptedDraft(new Booking(
            $this->origin->id(),
            $this->room,
            $this->organizer,
            $this->date,
            $this->range,
            $title,
            $this->description,
            $this->participants,
            'booked',
            $this->series,
            $this->origin->sourceId(),
        ));
    }

    /**
     * @return array<string, mixed>
     */
    public function view(): array
    {
        return [
            'room' => $this->room->view(),
            'date' => $this->date->text(),
            'timeFrom' => $this->range->start()->text(),
            'timeTo' => $this->range->end()->text(),
            'timeLabel' => $this->range->label(),
            'title' => $this->title,
            'description' => $this->description,
            'participants' => $this->participants->view(),
            'participantsCount' => $this->participants->count(),
            'ratio' => $this->participants->ratio($this->room),
            'fits' => $this->participants->fitsIn($this->room),
            'repeat' => $this->series->view(),
            'unchanged' => $this->unchanged(),
            'canSubmit' => $this->confirm()->accepted(),
            'originId' => $this->origin instanceof Booking ? $this->origin->id() : 0,
        ];
    }

    private function preview(string $title): Booking
    {
        return new Booking(
            9000,
            $this->room,
            $this->organizer,
            $this->date,
            $this->range,
            $title,
            $this->description,
            $this->participants,
            'booked',
            $this->series,
            0,
        );
    }

    private function copy(
        ?Room $room = null,
        ?CalendarDate $date = null,
        ?TimeRange $range = null,
        ?string $title = null,
        ?string $description = null,
        ?ParticipantList $participants = null,
        ?SeriesRule $series = null,
        ?Timeline $timeline = null,
    ): self {
        return new self(
            $room ?? $this->room,
            $this->organizer,
            $date ?? $this->date,
            $range ?? $this->range,
            $title ?? $this->title,
            $description ?? $this->description,
            $participants ?? $this->participants,
            $series ?? $this->series,
            $timeline ?? $this->timeline,
            $this->clock,
            $this->origin,
        );
    }
}
