<?php

declare(strict_types=1);

use Buildotter\Core\RandomMultiple;
use NereaEnrique\Standaaardise\Entity\Dinosaur;
use NereaEnrique\Standaaardise\Fixtures\Builder\BookingBuilder;
use NereaEnrique\Standaaardise\Fixtures\Builder\DinosaurBuilder;

function random(): Faker\Generator
{
    return Faker\Factory::create();
}

function aDinosaur(): DinosaurBuilder
{
    return DinosaurBuilder::random();
}

function anAdultDinosaur(): DinosaurBuilder
{
    return DinosaurBuilder::random()
        ->with(age: \random()->numberBetween(18, 100));
}

function aBabyDinosaur(): DinosaurBuilder
{
    return DinosaurBuilder::random()
        ->with(age: \random()->numberBetween(1, 17));
}

function when(?string $date): DateTimeImmutable
{
    if (null === $date) {
        return new DateTimeImmutable(
            timezone: new DateTimeZone('Europe/Paris'),
        );
    }

    return new DateTimeImmutable(
        $date,
        new \DateTimeZone('Europe/Paris'),
    );
}

function aTrex(): DinosaurBuilder
{
    return DinosaurBuilder::random()
        ->with(
            id: 'e8b534e4-50ac-44e9-a049-c37d6d080acc',
            name: 'Tyrannosaurus Rex',
            img: 'https://www.nationalgeographic.com/content/dam/animals/thumbs/rights-exempt/reptiles/t/tyrannosaurus-rex_thumb.ngsversion.1597688942681.adapt.1900.1.jpg',
            description: 'The most fearsome dinosaur of them all',
            height: 20,
            length: 40,
            weight: 8,
            age: 68,
        );
}

function aVelociraptor(): DinosaurBuilder
{
    return DinosaurBuilder::random()
        ->with(
            id: '9fb5254a-3a7a-44ee-a186-aae2fe1d6a6d',
            name: 'Velociraptor',
            img: 'https://www.nationalgeographic.com/content/dam/animals/thumbs/rights-exempt/reptiles/v/velociraptor_thumb.ngsversion.1597688942681.adapt.1900.1.jpg',
            description: 'Fast and smart',
            height: 1,
            length: 2,
            weight: 15,
            age: 23,
        );
}

/**
 * @return NereaEnrique\Standaaardise\Entity\Dinosaur[]
 */
function someDinosaurs(?int $numberOfItems = null): array
{
    return RandomMultiple::from(DinosaurBuilder::class, $numberOfItems);
}

/**
 * @return DinosaurBuilder[]
 */
function someDinosaursToBuild(?int $numberOfItems = null): array
{
    return RandomMultiple::toBuildFrom(DinosaurBuilder::class, $numberOfItems);
}

function aBooking(): BookingBuilder
{
    return BookingBuilder::random();
}

/**
 * @return NereaEnrique\Standaaardise\Entity\Booking[]
 */
function someBookings(?int $numberOfItems = null): array
{
    return RandomMultiple::from(BookingBuilder::class, $numberOfItems);
}

/**
 * @return NereaEnrique\Standaaardise\Entity\Booking[]
 */
function someBookingsForDinosaur(Dinosaur|DinosaurBuilder $dino, ?int $numberOfItems = null): array
{
    $bookings = RandomMultiple::toBuildFrom(BookingBuilder::class, $numberOfItems);
    $r = [];
    foreach ($bookings as $booking) {
        $r[] = $booking->for($dino)->build();
    }

    return $r;
}

/**
 * @return BookingBuilder[]
 */
function someBookingsToBuild(?int $numberOfItems = null): array
{
    return RandomMultiple::toBuildFrom(BookingBuilder::class, $numberOfItems);
}
