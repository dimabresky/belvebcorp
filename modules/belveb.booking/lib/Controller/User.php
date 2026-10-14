<?php

namespace Belveb\Booking\Controller;

use Belveb\Booking\Service\BookingDesk;
use Bitrix\Main\Engine\Controller;

/**
 * Employee lookup for the participant field.
 */
class User extends Controller
{
    /**
     * @return array{users: list<array{id: int, name: string}>}
     */
    public function searchAction(string $query = ''): array
    {
        return ['users' => BookingDesk::open()->searchPeople($query)];
    }
}
