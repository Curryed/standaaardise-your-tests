<?php

declare(strict_types=1);

namespace NereaEnrique\Standaaardise\Repository;

use loophp\collection\Collection;
use NereaEnrique\Standaaardise\Entity\Booking;
use NereaEnrique\Standaaardise\Entity\Dinosaur;
use NereaEnrique\Standaaardise\Exception\UnsuccesfulQueryException;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias]
final class BookingRepository implements BookingRepositoryInterface
{
    private const DB_PATH = __DIR__ . './../../var/data.db';

    public function __construct(
        private \SQLite3 $db = new \SQLite3(self::DB_PATH),
    ) {
        $this->createTableIfNotExists();
    }

    public function save(Booking $booking): void
    {
        $statement = $this->db->prepare(
            <<<SQL
INSERT INTO booking (id, dinosaur_id, booking_date)
VALUES (:id, :dinosaur_id, :when)
SQL,
        );

        $statement->bindValue(':id', $booking->id);
        $statement->bindValue(':dinosaur_id', $booking->dinosaurId);
        $statement->bindValue(':when', $booking->when->format(\DATE_W3C));

        $statement->execute();
    }

    public function fetchAll(): Collection
    {
        $query = $this->db->query(
            <<<SQL
SELECT * FROM booking
SQL,
        );

        if (false === $query instanceof \SQLite3Result) {
            throw new UnsuccesfulQueryException('Query failed');
        }

        $bookings = [];
        while ($row = $query->fetchArray(\SQLITE3_ASSOC)) {
            $bookings[] = Booking::create(
                id: $row['id'],
                dinosaurId: $row['dinosaur_id'],
                when: \DateTimeImmutable::createFromFormat(
                    \DATE_W3C,
                    (string) $row['booking_date'],
                    new \DateTimeZone('Europe/Paris'),
                ),
            );
        }

        return Collection::fromIterable($bookings);
    }

    public function fetchOneBy(array $criteria): ?Booking
    {
        $sql = 'SELECT * FROM booking';

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
                $value instanceof \DateTimeInterface => $statement->bindValue(':' . $key, $value->format(\DATE_W3C)),
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

        return Booking::create(
            $row['id'],
            $row['dinosaur_id'],
            \DateTimeImmutable::createFromFormat(
                \DATE_W3C,
                (string) $row['booking_date'],
                new \DateTimeZone('Europe/Paris'),
            ),
        );
    }

    public function new(Dinosaur $dinosaur, \DateTimeImmutable $when): Booking
    {
        return Booking::create(
            \random()->uuid(),
            $dinosaur->id,
            $when,
        );
    }

    public function reset(): void
    {
        $this->db->query('DROP TABLE IF EXISTS booking');
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists(): void
    {
        $this->db->query(
            <<<SQL
CREATE TABLE IF NOT EXISTS booking (
    id TEXT PRIMARY KEY,
    dinosaur_id TEXT,
    booking_date DATETIME
)
SQL,
        );
    }
}
