<?php

namespace App\Tests\App\Service;

use App\Entity\FruitsCollection;
use App\Service\StorageService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class StorageServiceTest extends TestCase
{
    private StorageService $storageService;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private FruitsCollection $mockFruitsCollection;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->mockFruitsCollection = $this->createMock(FruitsCollection::class);

        $this->storageService = new StorageService(
            $this->mockFruitsCollection,
            $this->logger,
            $this->entityManager
        );
    }

    public function testAddToCollectionInvalidJson(): void
    {
        $invalidJsonBody = '{ "name": "Bad Json"'; // Missing closing brace

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to parse JSON:');

        $this->storageService->addToCollection('fruits', $invalidJsonBody);
    }

    public function testAddToCollectionInvalidProduceType(): void
    {
        $jsonBody = json_encode([
            'name' => 'Unknown',
            'quantity' => 10,
            'unit' => 'unit'
        ]);

        $result = $this->storageService->addToCollection('unknownType', $jsonBody);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('invalid produce type', $result['message']);
    }

}
