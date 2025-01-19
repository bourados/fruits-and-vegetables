<?php

namespace App\Service;

use App\Entity\Fruit;
use App\Entity\FruitsCollection;
use App\Entity\Vegetable;
use App\Entity\VegetablesCollection;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ImportCollectionService extends AbstractService
{

    public function importRequestFromJson(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new FileNotFoundException('Unable to find file: ' .$filePath);
        }

        $fileContents = file_get_contents($filePath);
        if (false === $fileContents) {
            //TODO: use sprintf
            throw new \RuntimeException('Unable to read file: ' . $filePath);
        }

        $data = json_decode($fileContents, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Unable to parse JSON: ' . json_last_error_msg());
        }

        return $this->import($data);
    }

    private function import($data): array
    {
        $fruitsCollection = new FruitsCollection();
        $vegetablesCollection = new VegetablesCollection();

        foreach ($data as $produce) {
            if(!$this->isValidProduce($produce)) {
                $this->logger->warning('invalid produce: ' . json_encode($produce));
                continue;
            }

            if ($produce['type'] === 'fruit') {
                $fruit = (new Fruit())->setName($produce['name']);
                $fruit->setQuantity( $produce['unit'] === 'kg' ? $produce['quantity'] * 1000 : $produce['quantity']);
                if (!empty($produce['id'])){
                    $fruit->setId($produce['id']);
                }
                $fruit->setCollection($fruitsCollection);
                $fruitsCollection->add($fruit);
                continue;
            }

            if ($produce['type'] === 'vegetable') {
                $vegetable = (new Vegetable())->setName($produce['name']);
                $vegetable->setQuantity( $produce['unit'] === 'kg' ? $produce['quantity'] * 1000 : $produce['quantity']);
                if (!empty($produce['id'])){
                    $vegetable->setId($produce['id']);
                }
                $vegetable->setCollection($vegetablesCollection);
                $vegetablesCollection->add($vegetable);
                continue;
            }

            $this->logger->warning('invalid produce type: ' . json_encode($produce));
        }

        $this->entityManager->persist($fruitsCollection);
        $this->entityManager->persist($vegetablesCollection);
        $this->entityManager->flush();

        return ['fruits' => $fruitsCollection->list()->toArray(), 'vegetables' => $vegetablesCollection->list()->toArray()];
    }

}