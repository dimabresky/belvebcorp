<?php

namespace Belveb\Booking\Domain;

final class Nobody extends Person
{
    public function __construct()
    {
        parent::__construct(0, '');
    }

    public function same(Person $other): bool
    {
        return false;
    }

    /**
     * @return array{id: int, name: string}
     */
    public function view(): array
    {
        return [
            'id' => 0,
            'name' => '',
        ];
    }
}
