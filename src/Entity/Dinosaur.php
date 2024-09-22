<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Entity;

/**
 * @final
 */
readonly class Dinosaur
{
    private function __construct(
        public string $id,
        public string $name,
        public string $img,
        public string $description,
        public ?int $height,
        public ?int $length,
        public ?int $weight,
        public int $age,
    ) {}

    public static function create(
        string $id,
        string $name,
        string $img,
        string $description,
        ?int $height,
        ?int $length,
        ?int $weight,
        ?int $age,
    ): self {
        return new self(
            $id,
            $name,
            $img,
            $description,
            $height,
            $length,
            $weight,
            $age,
        );
    }

    public function isAdult(): bool
    {
        if ($this->age >= 18) {
            return true;
        }

        return false;
    }
}
