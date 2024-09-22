<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Repository;

use loophp\collection\Collection;
use NereaEnrique\Standaaardise\Entity\Booking;
use NereaEnrique\Standaaardise\Entity\Dinosaur;

interface BookingRepositoryInterface
{
    public function save(Booking $booking): void;

    /**
     * @return Collection<Booking>
     */
    public function fetchAll(): Collection;

    public function fetchOneBy(array $criteria): ?Booking;

    public function new(Dinosaur $dinosaur, \DateTimeImmutable $when): Booking;
}
