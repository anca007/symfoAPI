<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\models\Region;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{


    private Serie $serie;

    public function __construct()
    {
        $this->serie = new Serie();
        $this->serie->setName("Test")->setDescription("Description");
    }


    #[Route('/regions', name: 'region_api')]
    public function regions(SerializerInterface $serializer): Response
    {
        $content = file_get_contents('https://geo.api.gouv.fr/regions');
        dump($content);
        $regions = json_decode($content);
        dump($regions);
        $regions = $serializer->deserialize($content, Region::class.'[]', 'json');
        dd($regions);

       // return $this->json()
    }


    #[Route('/series', name: 'serie_read_api')]
    public function readSerie(SerializerInterface $serializer): Response    {

        return $this->json($this->serie, Response::HTTP_OK);
    }

    #[Route('/series/create', name: 'serie_create_api')]
    public function createSerie(): Response    {

        return $this->json(
            $this->serie,
            Response::HTTP_CREATED,
            [
                "Serie" => $this->generateUrl(
                    'serie_read_api',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ]
        );
    }

    #[Route('/series/update', name: 'serie_update_api')]
    public function updateSerie(SerializerInterface $serializer): Response    {

        $data = "{\"name\":\"Autre série\"}";

        //dd(json_decode($data));

        $serializer->deserialize(
            $data,
            Serie::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $this->serie]
        );

        return $this->json(
            $this->serie,
            Response::HTTP_NO_CONTENT,
        );
    }





}
