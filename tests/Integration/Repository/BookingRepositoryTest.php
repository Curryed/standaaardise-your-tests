<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Tests\Integration\Repository;

use loophp\collection\Collection;
use NereaEnrique\Standaaardise\Entity\Booking;
use NereaEnrique\Standaaardise\Repository\BookingRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class BookingRepositoryTest extends TestCase
{
    private BookingRepository $bookingRepository;

    protected function setUp(): void
    {
        $this->bookingRepository = new BookingRepository(new \SQLite3(':memory:'));
        $this->bookingRepository->reset();
    }

    #[Test]
    public function fetch_all(): void
    {
        // Arrange
        $bookings = \someBookings(42);
        foreach ($bookings as $booking) {
            $this->bookingRepository->save($booking);
        }

        // Act
        $actual = $this->bookingRepository->fetchAll();

        // Assert
        self::assertInstanceOf(Collection::class, $actual);
        self::assertTrue($actual->every(static fn ($booking): bool => $booking instanceof Booking));
        self::assertCount(42, $actual);
    }

    #[Test]
    public function fetch_one_by_id(): void
    {
        // Arrange

        // Act

        // Assert
    }

    #[Test]
    public function fetch_one_by_with_all_params(): void
    {
        // Arrange
        $bookings = \someBookings(20);
        foreach ($bookings as $booking) {
            $this->bookingRepository->save($booking);
        }
        $expected = $bookings[\array_rand($bookings)];

        // Act
        $actual = $this->bookingRepository->fetchOneBy([
            'dinosaur_id' => $expected->dinosaurId,
            'booking_date' => $expected->when,
        ]);

        // Assert
        self::assertEquals($expected, $actual);
    }

    #[Test]
    public function fetch_one_by_when_not_found(): void
    {
        // Arrange
        $bookings = \someBookings(20);
        foreach ($bookings as $booking) {
            $this->bookingRepository->save($booking);
        }
        $aBooking = \aBooking()->build();

        // Act
        $actual = $this->bookingRepository->fetchOneBy([
            'dinosaur_id' => $aBooking->dinosaurId,
            'booking_date' => $aBooking->when,
        ]);

        // Assert
        self::assertNull($actual);
    }
}
