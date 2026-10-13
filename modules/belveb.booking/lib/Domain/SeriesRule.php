<?php

namespace Belveb\Booking\Domain;

final class SeriesRule
{
    private const RULES = ['none', 'daily', 'weekly', 'monthly'];

    public function __construct(
        private string $rule,
        private CalendarDate $from,
        private int $horizonDays,
    ) {
        if (!in_array($this->rule, self::RULES, true)) {
            throw new \InvalidArgumentException('Unknown series rule');
        }
        if ($this->horizonDays < 1) {
            throw new \InvalidArgumentException('Series horizon must be positive');
        }
    }

    public function rule(): string
    {
        return $this->rule;
    }

    public function from(): CalendarDate
    {
        return $this->from;
    }

    public function label(): string
    {
        return match ($this->rule) {
            'daily' => 'Ежедневно, от ' . $this->from->text(),
            'weekly' => 'Еженедельно, от ' . $this->from->text(),
            'monthly' => 'Ежемесячно, от ' . $this->from->text(),
            default => 'Без повтора',
        };
    }

    public function same(self $other): bool
    {
        return $this->rule === $other->rule && $this->from->compare($other->from) === 0;
    }

    /**
     * @return list<CalendarDate>
     */
    public function occurrences(WorkCalendar $calendar): array
    {
        if ($this->rule === 'none') {
            return [$this->from];
        }

        $dates = [];
        $cursor = $this->from;
        $guard = 0;
        while (count($dates) < $this->horizonDays && $guard < 400) {
            $guard++;
            if ($calendar->isWorkDay($cursor) && $this->hits($cursor)) {
                $dates[] = $cursor;
            }
            $cursor = $cursor->plusDays(1);
        }

        return $dates;
    }

    /**
     * @return array{rule: string, from: string, label: string}
     */
    public function view(): array
    {
        return [
            'rule' => $this->rule,
            'from' => $this->from->text(),
            'label' => $this->label(),
        ];
    }

    private function hits(CalendarDate $date): bool
    {
        if ($date->compare($this->from) < 0) {
            return false;
        }

        return match ($this->rule) {
            'daily' => true,
            'weekly' => $date->weekday() === $this->from->weekday(),
            'monthly' => substr($date->text(), 0, 2) === substr($this->from->text(), 0, 2),
            default => $date->compare($this->from) === 0,
        };
    }
}
