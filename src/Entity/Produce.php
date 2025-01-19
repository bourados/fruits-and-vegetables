<?php

namespace App\Entity;

use App\Repository\ProduceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduceRepository::class)]
#[ORM\Table(name: 'produce')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['fruit' => Fruit::class, 'vegetable' => Vegetable::class])]
abstract class Produce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

//    #[ORM\Column(length: 50)]
//    private ?string $type = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $collection_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

//    public function setType(string $type): static
//    {
//        $this->type = $type;
//
//        return $this;
//    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCollectionId(): ?int
    {
        return $this->collection_id;
    }

    public function setCollectionId(int $collection_id): static
    {
        $this->collection_id = $collection_id;

        return $this;
    }
}
