<?php

namespace Belveb\Booking\Domain;

final class CalendarDate
{
    private \DateTimeImmutable $date;

    public function __construct(string $value)
    {
        $parsed = \DateTimeImmutable::createFromFormat('!d.m.Y', $value)
            ?: \DateTimeImmutable::createFromFormat('!Y-m-d', $value);

        $errors = \DateTimeImmutable::getLastErrors();
        $failed = $parsed === false
            || (is_array($errors) && (($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0));

        if ($failed) {
            throw new \InvalidArgumentException('CalendarDate expects d.m.Y');
        }

        $this->date = $parsed;
    }

    public function text(): string
    {
        return $this->date->format('d.m.Y');
    }

    public function iso(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function compare(self $other): int
    {
        return $this->iso() <=> $other->iso();
    }

    public function plusDays(int $days): self
    {
        $shifted = $this->date->modify(($days >= 0 ? '+' : '') . $days . ' days');

        return new self($shifted->format('d.m.Y'));
    }

    public function weekday(): int
    {
        return (int) $this->date->format('N');
    }
}
