<?php

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitRepository::class)]
class Fruit extends Produce
{

    #[ORM\ManyToOne(targetEntity: FruitsCollection::class, inversedBy: 'produce')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FruitsCollection $collection = null;

    public function getCollection(): ?FruitsCollection
    {
        return $this->collection;
    }

    public function setCollection(?FruitsCollection $collection): self
    {
        $this->collection = $collection;
        return $this;
    }
//    public function __construct()
//    {
//        $this->type = "fruit";
//    }

//    #[ORM\ManyToOne(targetEntity: FruitsCollection::class, inversedBy: 'fruits')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?FruitsCollection $collection = null;
//
//    public function getCollection(): ?FruitsCollection
//    {
//        return $this->collection;
//    }
//
//    public function setCollection(?FruitsCollection $collection): self
//    {
//        $this->collection = $collection;
//        return $this;
//    }
}
