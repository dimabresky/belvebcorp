<?php

namespace Belveb\Booking\Controller;

use Belveb\Booking\Service\BookingDesk;
use Bitrix\Main\Engine\Controller;

/**
 * Employee lookup for the participant field.
 *
 * This file stays on a lowercase path: Bitrix resolves belveb:booking.user.search to lib/controller/user.php.
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
