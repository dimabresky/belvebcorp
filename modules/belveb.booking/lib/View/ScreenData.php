<?php

namespace Belveb\Booking\View;

use Belveb\Booking\Fixture\FixtureCatalog;

final class ScreenData
{
    public function __construct(private string $imageBase)
    {
    }

    public function pages(): PageState
    {
        return new PageState(new FixtureCatalog($this->imageBase));
    }
}
