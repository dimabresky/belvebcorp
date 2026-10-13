<?php

namespace Belveb\Booking\Domain;

final class Grid
{
    public function __construct(
        private MinuteOfDay $from,
        private MinuteOfDay $to,
        private int $stepMinutes,
    ) {
        if ($this->stepMinutes < 1) {
            throw new \InvalidArgumentException('Grid step must be positive');
        }
        if ($this->to->total() <= $this->from->total()) {
            throw new \InvalidArgumentException('Grid end must be after start');
        }
    }

    public function stepMinutes(): int
    {
        return $this->stepMinutes;
    }

    public function from(): MinuteOfDay
    {
        return $this->from;
    }

    public function to(): MinuteOfDay
    {
        return $this->to;
    }

    public function align(TimeRange $range): TimeRange
    {
        $start = $range->start()->floorTo($this->stepMinutes);
        $end = $range->end()->ceilTo($this->stepMinutes);
        if ($end->total() <= $start->total()) {
            $end = $start->plus($this->stepMinutes);
        }

        return new TimeRange($start, $end);
    }

    public function contains(TimeRange $range): bool
    {
        return $range->start()->total() >= $this->from->total()
            && $range->end()->total() <= $this->to->total();
    }

    /**
     * @return list<TimeRange>
     */
    public function cells(): array
    {
        $cells = [];
        $cursor = $this->from;
        while ($cursor->total() < $this->to->total()) {
            $next = $cursor->plus($this->stepMinutes);
            if ($next->total() > $this->to->total()) {
                break;
            }
            $cells[] = new TimeRange($cursor, $next);
            $cursor = $next;
        }

        return $cells;
    }

    /**
     * @return array{workFrom: string, workTo: string, stepMinutes: int}
     */
    public function view(): array
    {
        return [
            'workFrom' => $this->from->text(),
            'workTo' => $this->to->text(),
            'stepMinutes' => $this->stepMinutes,
        ];
    }
}
