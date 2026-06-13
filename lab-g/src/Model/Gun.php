<?php
namespace App\Model;

use App\Service\Config;

class Gun
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $caliber = null;
    private ?int $magazineCapacity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Gun
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Gun
    {
        $this->name = $name;

        return $this;
    }

    public function getCaliber(): ?string
    {
        return $this->caliber;
    }

    public function setCaliber(?string $caliber): Gun
    {
        $this->caliber = $caliber;

        return $this;
    }

    public function getMagazineCapacity(): ?int
    {
        return $this->magazineCapacity;
    }

    public function setMagazineCapacity(?int $magazineCapacity): Gun
    {
        $this->magazineCapacity = $magazineCapacity;

        return $this;
    }

    public static function fromArray($array): Gun
    {
        $gun = new self();
        $gun->fill($array);

        return $gun;
    }

    public function fill($array): Gun
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['caliber'])) {
            $this->setCaliber($array['caliber']);
        }
        if (isset($array['magazine_capacity'])) {
            $this->setMagazineCapacity($array['magazine_capacity']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM gun';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $guns = [];
        $gunsArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($gunsArray as $gunArray) {
            $guns[] = self::fromArray($gunArray);
        }

        return $guns;
    }

    public static function find($id): ?Gun
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM gun WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $gunArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $gunArray) {
            return null;
        }
        $gun = Gun::fromArray($gunArray);

        return $gun;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getId()) {
            $sql = "INSERT INTO gun (name, caliber, magazine_capacity) VALUES (:name, :caliber, :magazine_capacity)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->getName(),
                'caliber' => $this->getCaliber(),
                'magazine_capacity' => $this->getMagazineCapacity(),
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
            $sql = "UPDATE gun SET name = :name, caliber = :caliber, magazine_capacity = :magazine_capacity WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':name' => $this->getName(),
                ':caliber' => $this->getCaliber(),
                ':magazine_capacity' => $this->getMagazineCapacity(),
                ':id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM gun WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $this->getId(),
        ]);

        $this->setId(null);
        $this->setName(null);
        $this->setCaliber(null);
        $this->setMagazineCapacity(null);
    }
}