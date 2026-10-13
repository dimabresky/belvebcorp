<?php

namespace Belveb\Booking\Domain;

final class Slot
{
    public function __construct(
        private TimeRange $range,
        private string $state,
        private string $title = '',
        private string $organizer = '',
    ) {
        $known = ['free', 'busy', 'mine', 'selected', 'now'];
        if (!in_array($this->state, $known, true)) {
            throw new \InvalidArgumentException('Unknown slot state');
        }
    }

    public function paint(TimeRange $selection): self
    {
        if ($this->state === 'busy' || $this->state === 'mine') {
            return $this;
        }
        if (!$this->range->overlaps($selection)) {
            return $this;
        }

        return new self($this->range, 'selected', $this->title, $this->organizer);
    }

    /**
     * @return array{from: string, to: string, state: string, title: string, organizer: string}
     */
    public function view(): array
    {
        return [
            'from' => $this->range->start()->text(),
            'to' => $this->range->end()->text(),
            'state' => $this->state,
            'title' => $this->title,
            'organizer' => $this->organizer,
        ];
    }
}
