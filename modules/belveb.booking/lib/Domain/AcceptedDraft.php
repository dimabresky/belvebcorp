<?php

namespace Belveb\Booking\Domain;

final class AcceptedDraft
{
    public function __construct(private Booking $booking)
    {
    }

    public function accepted(): bool
    {
        return true;
    }

    public function booking(): Booking
    {
        return $this->booking;
    }

    /**
     * @return array{accepted: bool, message: string}
     */
    public function view(): array
    {
        return [
            'accepted' => true,
            'message' => 'Встреча забронирована',
        ];
    }
}
