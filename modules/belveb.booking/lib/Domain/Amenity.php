<?php

namespace Belveb\Booking\Domain;

final class Amenity
{
    public function __construct(
        private string $name,
        private string $icon,
        private string $width,
        private string $height,
    ) {
    }

    /**
     * @return array{name: string, icon: string, width: string, height: string}
     */
    public function view(): array
    {
        return [
            'name' => $this->name,
            'icon' => $this->icon,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
