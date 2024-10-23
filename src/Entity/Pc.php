<?php

namespace App\Entity;

use App\Repository\PcRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PcRepository::class)]
class Pc
{
    #[ORM\Id]
    #[ORM\Column(name:'mac')]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'pcs')]
    private ?Dep $laps = null;

    public function getId()
    {
        return $this->id;
    }
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLaps(): ?Dep
    {
        return $this->laps;
    }

    public function setLaps(?Dep $laps): static
    {
        $this->laps = $laps;

        return $this;
    }
}
