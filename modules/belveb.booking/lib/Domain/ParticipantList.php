<?php

namespace Belveb\Booking\Domain;

final class ParticipantList
{
    /**
     * @param list<Person> $people
     */
    public function __construct(private array $people)
    {
        foreach ($this->people as $person) {
            if (!$person instanceof Person) {
                throw new \InvalidArgumentException('ParticipantList accepts people only');
            }
        }
    }

    public function count(): int
    {
        return count($this->people);
    }

    public function countLabel(): string
    {
        $count = $this->count();
        $mod10 = $count % 10;
        $mod100 = $count % 100;
        if ($mod10 === 1 && $mod100 !== 11) {
            $word = 'человек';
        } elseif ($mod10 >= 2 && $mod10 <= 4 && ($mod100 < 12 || $mod100 > 14)) {
            $word = 'человека';
        } else {
            $word = 'человек';
        }

        return $count . ' ' . $word;
    }

    public function with(Person $person): self
    {
        if ($person instanceof Nobody || $this->has($person)) {
            return $this;
        }

        $people = $this->people;
        $people[] = $person;

        return new self($people);
    }

    public function without(Person $person): self
    {
        $people = [];
        foreach ($this->people as $current) {
            if (!$current->same($person)) {
                $people[] = $current;
            }
        }

        return new self($people);
    }

    public function has(Person $person): bool
    {
        foreach ($this->people as $current) {
            if ($current->same($person)) {
                return true;
            }
        }

        return false;
    }

    public function fitsIn(Room $room): bool
    {
        return $this->count() <= $room->capacity();
    }

    public function ratio(Room $room): string
    {
        return $this->count() . '/' . $room->capacity();
    }

    public function same(self $other): bool
    {
        if ($this->count() !== $other->count()) {
            return false;
        }

        foreach ($this->people as $person) {
            if (!$other->has($person)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return list<array{id: int, name: string}>
     */
    public function view(): array
    {
        $rows = [];
        foreach ($this->people as $person) {
            $rows[] = $person->view();
        }

        return $rows;
    }
}
