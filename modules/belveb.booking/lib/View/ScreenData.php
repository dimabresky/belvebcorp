<?php

namespace Belveb\Booking\View;

use Belveb\Booking\Fixture\FixtureCatalog;

final class ScreenData
{
    public const IMAGE_BASE = '/local/components/belveb/booking.rooms/templates/.default/images';

    public function __construct(private string $imageBase)
    {
    }

    public static function open(): self
    {
        return new self(self::IMAGE_BASE);
    }

    public function pages(): PageState
    {
        return new PageState($this->catalog());
    }

    public function catalog(): FixtureCatalog
    {
        return new FixtureCatalog($this->imageBase);
    }
}
