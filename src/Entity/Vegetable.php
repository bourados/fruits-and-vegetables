<?php

namespace App\Entity;

use App\Repository\VegetableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VegetableRepository::class)]
class Vegetable extends Produce
{

    #[ORM\ManyToOne(targetEntity: VegetablesCollection::class, inversedBy: 'produce')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VegetablesCollection $collection = null;

    public function getCollection(): ?VegetablesCollection
    {
        return $this->collection;
    }

    public function setCollection(?VegetablesCollection $collection): self
    {
        $this->collection = $collection;
        return $this;
    }
    
//    public function __construct()
//    {
//        $this->type = "vegetable";
//    }
//
//    #[ORM\ManyToOne(targetEntity: VegetablesCollection::class, inversedBy: 'vegetables')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?VegetablesCollection $collection = null;
//
//    public function getCollection(): ?VegetablesCollection
//    {
//        return $this->collection;
//    }
//
//    public function setCollection(?VegetablesCollection $collection): self
//    {
//        $this->collection = $collection;
//        return $this;
//    }
}
