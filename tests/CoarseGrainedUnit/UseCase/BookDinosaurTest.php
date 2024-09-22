<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Tests\CoarsedGrainedUnit\UseCase;

use NereaEnrique\Standaaardise\Entity\Booking;
use NereaEnrique\Standaaardise\Exception\DinosaurCannotBeBookedException;
use NereaEnrique\Standaaardise\Repository\BookingRepository;
use NereaEnrique\Standaaardise\Repository\BookingRepositoryInterface;
use NereaEnrique\Standaaardise\UseCase\BookDinosaur;
use NereaEnrique\Standaaardise\UseCase\BookResult;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class BookDinosaurTest extends TestCase
{
    private BookDinosaur $bookDinosaur;
    private BookingRepositoryInterface $bookingRepository;

    protected function setUp(): void
    {
        $this->bookingRepository = new BookingRepository(new \SQLite3(':memory:'));
        $this->bookingRepository->reset();
        $this->bookDinosaur = new BookDinosaur($this->bookingRepository);

        $bookings = \someBookings(5);
        foreach ($bookings as $booking) {
            $this->bookingRepository->save($booking);
        }
    }

    #[Test]
    public function it_throws_an_exception_when_booking_in_the_past(): void
    {
        // Arrange
        $dinosaur = \anAdultDinosaur()
            ->build();
        $when = \when('yesterday');

        // Assert / Expect
        self::expectException(DinosaurCannotBeBookedException::class);
        self::expectExceptionMessage('Cannot book a dinosaur in the past. BACK TO THE FUTURE!');

        // Act
        ($this->bookDinosaur)($dinosaur, $when);
    }

    #[Test]
    public function it_cannot_be_booked_if_it_is_not_an_adult(): void
    {
        // Arrange
        $dinosaur = \aBabyDinosaur()
            ->build();
        $when = \when('+3 months');

        // Act
        $actual = ($this->bookDinosaur)($dinosaur, $when);

        // Assert
        self::assertSame(
            BookResult::NotAnAdult,
            $actual,
        );
    }

    #[Test]
    public function it_cannot_be_booked_if_it_is_already_booked(): void
    {
        // Arrange
        $aDinosaur = \anAdultDinosaur()
            ->build();
        $when = \when('+3 months');

        $this->bookingRepository->save(
            \aBooking()
                ->for($aDinosaur)
                ->when($when)
            ->build(),
        );

        // Act
        $actual = ($this->bookDinosaur)($aDinosaur, $when);

        // Assert
        self::assertSame(
            BookResult::AlreadyBooked,
            $actual,
        );
    }

    #[Test]
    public function it_can_be_booked(): void
    {
        // Arrange
        $dinosaur = \anAdultDinosaur()
            ->build();
        $when = \when('2024-10-10');

        $bookings = $this->bookingRepository->fetchAll();
        $initialCount = $bookings->count();

        // Act
        $actual = ($this->bookDinosaur)($dinosaur, $when);

        // Assert
        self::assertSame(BookResult::Booked, $actual);

        $bookings = $this->bookingRepository->fetchAll();
        self::assertSame($initialCount + 1, $bookings->count());
        self::assertSameBooking(
            expectedDinosaurId: $dinosaur->id,
            expectedWhen: \when('2024-10-10'),
            actual: $bookings->last(),
        );
    }

    private function assertSameBooking(
        string $expectedDinosaurId,
        \DateTimeImmutable $expectedWhen,
        Booking $actual,
    ): void {
        self::assertSame($expectedDinosaurId, $actual->dinosaurId);
        self::assertEquals($expectedWhen, $actual->when);
    }
}
