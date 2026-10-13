<?php

namespace Belveb\Booking\Domain;

final class Room
{
    /**
     * @param list<Amenity> $amenities
     */
    public function __construct(
        private string $code,
        private string $label,
        private string $title,
        private string $image,
        private int $capacity,
        private int $floor,
        private string $type,
        private array $amenities,
        private Grid $grid,
        private string $detailUrl,
    ) {
        if ($this->code === '' || $this->capacity < 1 || $this->floor < 1) {
            throw new \InvalidArgumentException('Room is incomplete');
        }
    }

    public function code(): string
    {
        return $this->code;
    }

    public function capacity(): int
    {
        return $this->capacity;
    }

    public function floor(): int
    {
        return $this->floor;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function grid(): Grid
    {
        return $this->grid;
    }

    public function fits(ParticipantList $participants): bool
    {
        return $participants->fitsIn($this);
    }

    public function availableFor(TimeRange $range, Timeline $timeline): bool
    {
        return $timeline->accepts($range);
    }

    /**
     * @return array<string, mixed>
     */
    public function view(): array
    {
        $amenities = [];
        foreach ($this->amenities as $amenity) {
            $amenities[] = $amenity->view();
        }

        return [
            'code' => $this->code,
            'label' => $this->label,
            'title' => $this->title,
            'image' => $this->image,
            'capacity' => $this->capacity,
            'capacityLabel' => 'до ' . $this->capacity . ' человек',
            'floor' => $this->floor,
            'floorLabel' => $this->floor . ' этаж',
            'type' => $this->type,
            'amenities' => $amenities,
            'detailUrl' => $this->detailUrl,
            'grid' => $this->grid->view(),
        ];
    }
}
