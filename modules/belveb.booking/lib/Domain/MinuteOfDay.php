<?php

namespace Belveb\Booking\Domain;

final class MinuteOfDay
{
    private int $minutes;

    public function __construct(string $hhmm)
    {
        if (!preg_match('/^(\d{2}):(\d{2})$/', $hhmm, $match)) {
            throw new \InvalidArgumentException('MinuteOfDay expects HH:MM');
        }

        $hour = (int) $match[1];
        $minute = (int) $match[2];
        if ($hour > 23 || $minute > 59) {
            throw new \InvalidArgumentException('MinuteOfDay is out of range');
        }

        $this->minutes = $hour * 60 + $minute;
    }

    public function text(): string
    {
        return sprintf('%02d:%02d', intdiv($this->minutes, 60), $this->minutes % 60);
    }

    public function total(): int
    {
        return $this->minutes;
    }

    public function compare(self $other): int
    {
        return $this->minutes <=> $other->minutes;
    }

    public function floorTo(int $step): self
    {
        $this->assertStep($step);
        $floored = intdiv($this->minutes, $step) * $step;

        return new self($this->format($floored));
    }

    public function ceilTo(int $step): self
    {
        $this->assertStep($step);
        $ceiled = (int) (ceil($this->minutes / $step) * $step);
        if ($ceiled >= 24 * 60) {
            $ceiled = 24 * 60 - $step;
        }

        return new self($this->format($ceiled));
    }

    public function plus(int $delta): self
    {
        $next = $this->minutes + $delta;
        if ($next < 0 || $next >= 24 * 60) {
            throw new \InvalidArgumentException('MinuteOfDay shift leaves the day');
        }

        return new self($this->format($next));
    }

    private function assertStep(int $step): void
    {
        if ($step < 1) {
            throw new \InvalidArgumentException('Step must be positive');
        }
    }

    private function format(int $minutes): string
    {
        return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
    }
}
