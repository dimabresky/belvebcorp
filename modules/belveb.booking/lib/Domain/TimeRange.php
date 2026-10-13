<?php

namespace Belveb\Booking\Domain;

final class TimeRange
{
    public function __construct(
        private MinuteOfDay $start,
        private MinuteOfDay $end,
    ) {
        if ($this->end->total() <= $this->start->total()) {
            throw new \InvalidArgumentException('TimeRange end must be after start');
        }
    }

    public function start(): MinuteOfDay
    {
        return $this->start;
    }

    public function end(): MinuteOfDay
    {
        return $this->end;
    }

    public function overlaps(self $other): bool
    {
        return $this->start->total() < $other->end->total()
            && $this->end->total() > $other->start->total();
    }

    public function durationMinutes(): int
    {
        return $this->end->total() - $this->start->total();
    }

    public function withDuration(int $minutes): self
    {
        if ($minutes < 1) {
            throw new \InvalidArgumentException('Duration must be positive');
        }

        return new self($this->start, $this->start->plus($minutes));
    }

    public function label(): string
    {
        return $this->start->text() . ' – ' . $this->end->text();
    }

    public function covers(MinuteOfDay $moment): bool
    {
        return $moment->total() >= $this->start->total() && $moment->total() < $this->end->total();
    }

    public function same(self $other): bool
    {
        return $this->start->total() === $other->start->total()
            && $this->end->total() === $other->end->total();
    }
}
