<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractService
{
    protected LoggerInterface $logger;
    protected EntityManagerInterface $entityManager;
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager){
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }
    protected function isValidProduce(array $produce): bool
    {
        if (array_key_exists('name', $produce) && array_key_exists('type', $produce) && array_key_exists('quantity', $produce)) {
            return true;
        }

        return false;
    }
}