<?php

namespace Belveb\Booking\Domain;

class Person
{
    public function __construct(
        private int $id,
        private string $name,
    ) {
        $empty = $this->id < 1 || $this->name === '';
        if ($empty && !$this instanceof Nobody) {
            throw new \InvalidArgumentException('Person id and name are required');
        }
        if ($this->id < 0) {
            throw new \InvalidArgumentException('Person id must not be negative');
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function known(): bool
    {
        return $this->id > 0;
    }

    public function same(self $other): bool
    {
        return $this->known() && $other->known() && $this->id === $other->id;
    }

    /**
     * @return array{id: int, name: string}
     */
    public function view(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
