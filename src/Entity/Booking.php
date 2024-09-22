<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Entity;

/**
 * @final
 */
readonly class Booking
{
    private function __construct(
        public string $id,
        public string $dinosaurId,
        public \DateTimeImmutable $when,
    ) {}

    public static function create(
        string $id,
        string $dinosaurId,
        \DateTimeImmutable $when,
    ): self {
        return new self($id, $dinosaurId, $when);
    }
}
