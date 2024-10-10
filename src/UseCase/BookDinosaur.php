<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\UseCase;

use NereaEnrique\Standaaardise\Entity\Booking;
use NereaEnrique\Standaaardise\Entity\Dinosaur;
use NereaEnrique\Standaaardise\Exception\DinosaurCannotBeBookedException;
use NereaEnrique\Standaaardise\Repository\BookingRepositoryInterface;

final class BookDinosaur
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
    ) {}

    public function __invoke(Dinosaur $dinosaur, \DateTimeImmutable $when): BookResult
    {
        if (false === $dinosaur->isAdult()) {
            return BookResult::NotAnAdult;
        }

        if (true === $this->isDinosaurBooked($dinosaur, $when)) {
            return BookResult::AlreadyBooked;
        }

        $booking = $this->bookingRepository->new($dinosaur, $when);
        $this->bookingRepository->save($booking);

        return BookResult::Booked;
    }

    private function isDinosaurBooked(Dinosaur $dinosaur, \DateTimeImmutable $dateTimeImmutable): bool
    {
        $booking = $this->bookingRepository->fetchOneBy([
            'dinosaur_id' => $dinosaur->id,
            'booking_date' => $dateTimeImmutable,
        ]);

        return $booking instanceof Booking;
    }
}
