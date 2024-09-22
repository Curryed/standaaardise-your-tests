<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Tests\Integration\Repository;

use loophp\collection\Collection;
use NereaEnrique\Standaaardise\Entity\Dinosaur;
use NereaEnrique\Standaaardise\Repository\DinosaursRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DinosaursRepositoryTest extends TestCase
{
    private DinosaursRepository $dinosaursRepository;

    protected function setUp(): void
    {
        $this->dinosaursRepository = new DinosaursRepository(new \SQLite3(':memory:'));
        $this->dinosaursRepository->reset();
    }

    #[Test]
    public function fetch_all(): void
    {
        // Arrange
        $dinosaurs = \someDinosaurs(42);
        foreach ($dinosaurs as $dinosaur) {
            $this->dinosaursRepository->save($dinosaur);
        }

        // Act
        $actual = $this->dinosaursRepository->fetchAll();

        // Assert
        self::assertInstanceOf(Collection::class, $actual);
        self::assertTrue($actual->every(static fn ($booking): bool => $booking instanceof Dinosaur));
        self::assertCount(42, $actual);
    }

    #[Test]
    #[DataProvider('withCriteriaProvider')]
    public function fetch_one_by(callable $criteria): void
    {
        // Arrange
        $dinosaurs = \someDinosaurs(20);
        foreach ($dinosaurs as $dinosaur) {
            $this->dinosaursRepository->save($dinosaur);
        }
        $expected = $dinosaurs[\array_rand($dinosaurs)];

        // Act
        $actual = $this->dinosaursRepository->fetchOneBy($criteria($expected));

        // Assert
        self::assertEquals($expected, $actual);
    }

    public static function withCriteriaProvider(): \Generator
    {
        $criteria = static function (array $criteria): callable {
            return static function (Dinosaur $dinosaur) use ($criteria): array {
                $c = [];
                foreach ($criteria as $key) {
                    $c[$key] = match ($key) {
                        'id' => $dinosaur->id,
                        'name' => $dinosaur->name,
                        'img' => $dinosaur->img,
                        'description' => $dinosaur->description,
                        'height' => $dinosaur->height,
                        'length' => $dinosaur->length,
                        'weight' => $dinosaur->weight,
                        'age' => $dinosaur->age,
                    };
                }

                return $c;
            };
        };

        yield 'by id' => [
            'criteria' => $criteria(['id']),
        ];

        yield 'by name' => [
            'criteria' => $criteria(['name']),
        ];

        yield 'by description' => [
            'criteria' => $criteria(['description']),
        ];

        yield 'by all' => [
            'criteria' => $criteria(['id', 'name', 'img', 'description', 'height', 'length', 'weight', 'age']),
        ];

        yield 'by id and name' => [
            'criteria' => $criteria(['id', 'name']),
        ];
    }

    #[Test]
    public function fetch_one_by_when_not_found(): void
    {
        // Arrange
        $dinosaurs = \someDinosaurs(20);
        foreach ($dinosaurs as $dinosaur) {
            $this->dinosaursRepository->save($dinosaur);
        }
        $aDinosaur = \aDinosaur()->build();

        // Act
        $actual = $this->dinosaursRepository->fetchOneBy([
            'id' => $aDinosaur->id,
        ]);

        // Assert
        self::assertNull($actual);
    }
}
