<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Fixtures\Builder;

use Buildotter\Core\BuildableWithArgUnpacking;
use Buildotter\Core\Buildatable;
use NereaEnrique\Standaaardise\Entity\Dinosaur;

/**
 * @implements Buildatable<Dinosaur>
 */
final class DinosaurBuilder implements Buildatable
{
    use BuildableWithArgUnpacking;

    public function __construct(
        public string $id,
        public string $name,
        public string $img,
        public string $description,
        public ?int $height,
        public ?int $length,
        public ?int $weight,
        public ?int $age,
    ) {}

    public static function random(): static
    {
        $random = \random();

        return new self(
            id: $random->uuid(),
            name: $random->word(),
            img: $random->url(),
            description: $random->sentence(),
            height: $random->numberBetween(1, 100),
            length: $random->numberBetween(1, 100),
            weight: $random->numberBetween(1, 100),
            age: $random->numberBetween(1, 100),
        );
    }

    public function build(): Dinosaur
    {
        return Dinosaur::create(
            id: $this->id,
            name: $this->name,
            img: $this->img,
            description: $this->description,
            height: $this->height,
            length: $this->length,
            weight: $this->weight,
            age: $this->age,
        );
    }
}
