<?php

namespace App\Entity;

use App\Repository\GunsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GunsRepository::class)]
class Guns
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $caliber = null;

    #[ORM\Column]
    private ?int $magazineCapacity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCaliber(): ?string
    {
        return $this->caliber;
    }

    public function setCaliber(string $caliber): static
    {
        $this->caliber = $caliber;

        return $this;
    }

    public function getMagazineCapacity(): ?int
    {
        return $this->magazineCapacity;
    }

    public function setMagazineCapacity(int $magazineCapacity): static
    {
        $this->magazineCapacity = $magazineCapacity;

        return $this;
    }
}
