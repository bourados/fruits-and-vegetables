<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'fruits_collection')]
class FruitsCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: Fruit::class, mappedBy: 'collection', cascade: ['persist', 'remove'])]
    private Collection $produce;

    public function __construct()
    {
        $this->produce = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function add(Produce $produce): void
    {
        if (!$this->produce->contains($produce)) {
            $this->produce->add($produce);
        }
    }

    public function remove(Produce $produce): void
    {
        $this->produce->removeElement($produce);
    }

    /**
     * Get only fruits from the collection.
     *
     * @return Collection<int, Fruit>
     */
    public function list(): Collection
    {
        return $this->produce->filter(fn(Produce $item) => $item instanceof Fruit);
    }

    public function search($filters): Collection
    {
        $criteria = Criteria::create();
        foreach ($filters as $column => $value) {
            $criteria->andWhere(Criteria::expr()->eq($column, $value));
        }

        return $this->produce->matching($criteria);
    }

}