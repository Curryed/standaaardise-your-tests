<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Repository;

use loophp\collection\Collection;
use NereaEnrique\Standaaardise\Entity\Dinosaur;

interface DinosaursRepositoryInterface
{
    public function save(Dinosaur $dinosaur): void;

    public function fetchAll(): Collection;

    public function fetchOneBy(array $criteria): ?Dinosaur;

    public function new(
        string $name,
        string $img,
        string $description,
        ?int $height,
        ?int $length,
        ?int $weight,
        ?int $age,
    ): Dinosaur;
}
