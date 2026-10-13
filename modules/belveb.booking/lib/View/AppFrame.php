<?php

namespace Belveb\Booking\View;

use Bitrix\Main\UI\Extension;
use Bitrix\Main\Web\Json;

final class AppFrame
{
    /**
     * @param array<string, mixed> $state
     */
    public function __construct(private array $state)
    {
    }

    public function render(): void
    {
        Extension::load('belveb.booking');
        echo '<div id="belveb-booking-app"></div>';
        echo '<script>BX.ready(function () { BX.Belveb.Booking.mount('
            . Json::encode($this->state)
            . '); });</script>';
    }
}
