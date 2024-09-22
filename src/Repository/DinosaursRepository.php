<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Repository;

use loophp\collection\Collection;
use NereaEnrique\Standaaardise\Entity\Dinosaur;
use NereaEnrique\Standaaardise\Exception\UnsuccesfulQueryException;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
final class DinosaursRepository implements DinosaursRepositoryInterface
{
    private const DB_PATH = __DIR__ . './../../var/data.db';

    public function __construct(
        private \SQLite3 $db = new \SQLite3(self::DB_PATH),
    ) {
        $this->createTableIfNotExists();
    }

    public function save(Dinosaur $dinosaur): void
    {
        $statement = $this->db->prepare(
            <<<SQL
INSERT INTO dinosaurs (id, name, img, description, height, length, weight, age)
VALUES (:id, :name, :img, :description, :height, :length, :weight, :age)
SQL,
        );

        $statement->bindValue(':id', $dinosaur->id);
        $statement->bindValue(':name', $dinosaur->name);
        $statement->bindValue(':img', $dinosaur->img);
        $statement->bindValue(':description', $dinosaur->description);
        $statement->bindValue(':height', $dinosaur->height, \SQLITE3_INTEGER);
        $statement->bindValue(':length', $dinosaur->length, \SQLITE3_INTEGER);
        $statement->bindValue(':weight', $dinosaur->weight, \SQLITE3_INTEGER);
        $statement->bindValue(':age', $dinosaur->age, \SQLITE3_INTEGER);

        $success = $statement->execute();
        if (false === $success) {
            throw new UnsuccesfulQueryException('Query failed');
        }
    }

    public function fetchAll(): Collection
    {
        $query = $this->db->query(
            <<<SQL
SELECT * FROM dinosaurs
SQL,
        );

        if (false === $query instanceof \SQLite3Result) {
            throw new UnsuccesfulQueryException('Query failed');
        }

        $dinosaurs = [];
        while ($row = $query->fetchArray(\SQLITE3_ASSOC)) {
            $dinosaurs[] = Dinosaur::create(
                id: $row['id'],
                name: $row['name'],
                img: $row['img'],
                description: $row['description'],
                height: $row['height'],
                length: $row['length'],
                weight: $row['weight'],
                age: $row['age'],
            );
        }

        return Collection::fromIterable($dinosaurs);
    }

    public function fetchOneBy(array $criteria): ?Dinosaur
    {
        $sql = 'SELECT * FROM dinosaurs';

        foreach ($criteria as $key => $value) {
            if (false === \str_contains($sql, 'WHERE')) {
                $sql .= \sprintf(' WHERE %s = :%s', $key, $key);
            }

            $sql .= \sprintf(' AND %s = :%s', $key, $key);
        }
        $sql .= ' LIMIT 1';

        $statement = $this->db->prepare($sql);

        foreach ($criteria as $key => $value) {
            match (true) {
                null === $value => null,
                default => $statement->bindValue(':' . $key, $value),
            };
        }

        $query = $statement->execute();
        if (false === $query instanceof \SQLite3Result) {
            return null;
        }

        $row = $query->fetchArray(\SQLITE3_ASSOC);
        if (false === \is_array($row)) {
            return null;
        }

        return Dinosaur::create(
            id: $row['id'],
            name: $row['name'],
            img: $row['img'],
            description: $row['description'],
            height: $row['height'],
            length: $row['length'],
            weight: $row['weight'],
            age: $row['age'],
        );
    }

    public function new(string $name, string $img, string $description, ?int $height, ?int $length, ?int $weight, ?int $age): Dinosaur
    {
        return Dinosaur::create(
            id: \random()->uuid(),
            name: $name,
            img: $img,
            description: $description,
            height: $height,
            length: $length,
            weight: $weight,
            age: $age,
        );
    }

    public function reset(): void
    {
        $this->db->query('DROP TABLE IF EXISTS dinosaurs');
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists(): void
    {
        $this->db->query(
            <<<SQL
CREATE TABLE IF NOT EXISTS dinosaurs (
    id TEXT PRIMARY KEY,
    name TEXT,
    img TEXT,
    description TEXT,
    height INTEGER NULLABLE,
    length INTEGER NULLABLE,
    weight INTEGER NULLABLE,
    age INTEGER NULLABLE
)
SQL,
        );
    }
}
