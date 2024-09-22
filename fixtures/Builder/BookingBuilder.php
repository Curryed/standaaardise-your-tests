<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Fixtures\Builder;

use Buildotter\Core\BuildableWithArgUnpacking;
use Buildotter\Core\Buildatable;
use NereaEnrique\Standaaardise\Entity\Booking;
use NereaEnrique\Standaaardise\Entity\Dinosaur;

/**
 * @implements Buildatable<Booking>
 */
final class BookingBuilder implements Buildatable
{
    use BuildableWithArgUnpacking;

    public function __construct(
        public string $id,
        public string $dinosaurId,
        public \DateTimeImmutable $when,
    ) {}

    public static function random(): static
    {
        $random = \random();

        return new static(
            id: $random->uuid(),
            dinosaurId: $random->uuid(),
            when: \DateTimeImmutable::createFromMutable($random->dateTime())->setTimezone(new \DateTimeZone('Europe/Paris')),
        );
    }

    public function build(): Booking
    {
        return Booking::create(
            $this->id,
            $this->dinosaurId,
            $this->when,
        );
    }

    public function for(Dinosaur|DinosaurBuilder $dinosaur): self
    {
        return $this->with(dinosaurId: $dinosaur->id);
    }

    public function when(\DateTimeImmutable|string $when): static
    {
        return $this->with(
            when: match (true) {
                $when instanceof \DateTimeImmutable => $when,
                default => \DateTimeImmutable::createFromFormat('Y-m-d', $when)
                    ->setTimezone(new \DateTimeZone('Europe/Paris'))
            }
        );
    }
}
