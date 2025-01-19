<?php

namespace App\Controller\Api;

use App\Service\ImportCollectionService;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class ApiController extends AbstractController
{

    #[Route('/import_json', name: 'import_json', methods: ['GET'])]
    public function importJson(ImportCollectionService $collectionImporterService): Response
    {
        try {
            $collections = $collectionImporterService->importRequestFromJson('./../request.json');
        }
        catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response('File imported successfully : ' . json_encode($collections), Response::HTTP_OK);
    }

    #[Route('/{type}', methods: ['GET'])]
    public function produce(
        string $type,
        #[MapQueryParameter] ?string $name,
        #[MapQueryParameter] ?string $quantity,
        #[MapQueryParameter] ?string $unit,
        StorageService $storageService): Response
    {
        return $this->json($storageService->getCollection($type, $name, $quantity, $unit), Response::HTTP_OK);
    }

    #[Route('/{type}', methods: ['PUT'])]
    public function add(string $type, Request $request, StorageService $storageService): Response
    {
        $produce = $request->getContent();
        try {
            $result = $storageService->addToCollection($type, $produce);
        }
        catch (\Exception $exception){
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (!$result['success']) {
            return $this->json($result['message'], Response::HTTP_BAD_REQUEST);
        }

        return new Response('File imported successfully : ' . $produce, Response::HTTP_OK);
    }
}