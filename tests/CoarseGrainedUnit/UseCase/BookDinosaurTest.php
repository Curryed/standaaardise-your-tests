<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Tests\CoarsedGrainedUnit\UseCase;

use NereaEnrique\Standaaardise\Entity\Booking;
use NereaEnrique\Standaaardise\Entity\Dinosaur;
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
        self::markTestSkipped('Not implemented yet.');
        // Arrange

        // Act

        // Assert
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
        $aDinosaur = Dinosaur::create(
            id: 'e8b534e4-50ac-44e9-a049-c37d6d080acc',
            name: 'Tyrannosaurus Rex',
            img: 'https://www.nationalgeographic.com/content/dam/animals/thumbs/rights-exempt/reptiles/t/tyrannosaurus-rex_thumb.ngsversion.1597688942681.adapt.1900.1.jpg',
            description: 'The most fearsome dinosaur of them all',
            height: 20,
            length: 40,
            weight: 8,
            age: 68,
        );
        $when = new \DateTimeImmutable('+3 months');

        $this->bookingRepository->save(
            Booking::create(
                id: 'e8b534e4-50ac-44e9-a049-c37d6d080acc',
                dinosaurId: $aDinosaur->id,
                when: $when,
            ),
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
