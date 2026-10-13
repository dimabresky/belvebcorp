<?php

namespace Belveb\Booking\Domain;

final class RejectedDraft
{
    public function __construct(
        private string $code,
        private string $message,
    ) {
    }

    public function accepted(): bool
    {
        return false;
    }

    public function blocked(): bool
    {
        return true;
    }

    public function code(): string
    {
        return $this->code;
    }

    /**
     * @return array{accepted: bool, code: string, message: string}
     */
    public function view(): array
    {
        return [
            'accepted' => false,
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
}
