<?php

namespace App\Entity;

use App\Repository\DepRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepRepository::class)]
class Dep
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $nb_laptop = null;

    /**
     * @var Collection<int, Pc>
     */
    #[ORM\OneToMany(targetEntity: Pc::class, mappedBy: 'laps')]
    private Collection $pcs;

    public function __construct()
    {
        $this->pcs = new ArrayCollection();
    }

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

    public function getNbLaptop(): ?int
    {
        return $this->nb_laptop;
    }

    public function setNbLaptop(int $nb_laptop): static
    {
        $this->nb_laptop = $nb_laptop;

        return $this;
    }

    /**
     * @return Collection<int, Pc>
     */
    public function getPcs(): Collection
    {
        return $this->pcs;
    }

    public function addPc(Pc $pc): static
    {
        if (!$this->pcs->contains($pc)) {
            $this->pcs->add($pc);
            $pc->setLaps($this);
        }

        return $this;
    }

    public function removePc(Pc $pc): static
    {
        if ($this->pcs->removeElement($pc)) {
            // set the owning side to null (unless already changed)
            if ($pc->getLaps() === $this) {
                $pc->setLaps(null);
            }
        }

        return $this;
    }
}
