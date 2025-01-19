<?php

namespace App\Service;

use App\Entity\Fruit;
use App\Entity\FruitsCollection;
use App\Entity\Vegetable;
use App\Entity\VegetablesCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class StorageService extends AbstractService
{
    protected FruitsCollection $fruitsCollection;

    public function __construct(
        FruitsCollection $fruitsCollection,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager
    )
    {
        $this->fruitsCollection = $fruitsCollection;
        parent::__construct($logger, $entityManager);
    }

    public function getCollection(string $type, ?string $name, ?string $quantity, ?string $unit): array
    {
        $filters = $this->constructFilters($name, $quantity);
        if ($type === 'fruits'){
            $fruits = $this->entityManager->getRepository(FruitsCollection::class)->findAll();
            return $this->constructReturnCollection($fruits, $filters, $unit);
        }
        if ($type === 'vegetables'){
            $vegetables = $this->entityManager->getRepository(VegetablesCollection::class)->findAll();
            return $this->constructReturnCollection($vegetables, $filters, $unit);
        }
    }

    private function constructReturnCollection(array $produces, array $filters, ?string $unit=''):array
    {
        return array_map(fn($collection) => [
            'id' => $collection->getId(),
            'items' => array_map(
                fn($item) => [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'quantity' => (strtolower($unit) === 'kg') ? $item->getQuantity()/1000 : $item->getQuantity(),
                ],
                $collection->search($filters)->toArray()
            )
        ], $produces);
    }

    private function constructFilters(?string $name, ?int $quantity):array
    {
        $filters = [];
        if ($name){
            $filters['name'] = $name;
        }
        if ($quantity){
            $filters['quantity'] = $quantity;
        }
        return $filters;
    }


    public function addToCollection(string $type, string $body): ?array
    {
        $produce = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Unable to parse JSON: ' . json_last_error_msg());
        }

        $produce['type'] = $type === 'fruits' ? 'fruit' : ($type === 'vegetables' ? 'vegetable' : '');

        if(!$this->isValidProduce($produce)) {
            $this->logger->warning('invalid produce: ' . json_encode($produce));
            return ['success' => false, 'message' => 'invalid produce: ' . json_encode($produce)];
        }

        if ($produce['type'] === 'fruit') {
            $this->saveProduceInCollection(FruitsCollection::class, Fruit::class, $produce);
            return ['success' => true, 'message' => 'fruit imported successfully: ' . json_encode($produce)];
        }

        if ($produce['type'] === 'vegetable') {
            $this->saveProduceInCollection(VegetablesCollection::class, Vegetable::class, $produce);
            return ['success' => true, 'message' => 'vegetable imported successfully: ' . json_encode($produce)];
        }

        return ['success' => false, 'message' => 'invalid produce type : ' . $type];
    }

    protected function saveProduceInCollection(string $collectionClassName, string $entityClassName, array $produce): void
    {
        $collection = $this->entityManager->getRepository($collectionClassName)->findAll();
        $collection = array_shift($collection);
        $produceEntry = (new $entityClassName())->setName($produce['name']);
        $produceEntry->setQuantity( $produce['unit'] === 'kg' ? $produce['quantity'] * 1000 : $produce['quantity']);
        if (!empty($produce['id'])){
            $produceEntry->setId($produce['id']);
        }
        $produceEntry->setCollection($collection);
        $collection->add($produceEntry);

        $this->entityManager->persist($collection);
        $this->entityManager->flush();
    }

}
