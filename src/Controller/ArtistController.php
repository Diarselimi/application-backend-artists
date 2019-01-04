<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Utils\EntitySerialization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    /**
     * @Route("/artists", name="artist")
     */
    public function index(): JsonResponse
    {

        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository(Artist::class)->findAll();

        return new JsonResponse(EntitySerialization::serialize($artists));
    }

    /**
     * @Route("/artist/{id}", name="find_artist")
     * @param Request $request
     * @param Artist|null $artist
     * @return JsonResponse
     */
    public function artistAction(Request $request, Artist $artist = null): JsonResponse
    {
        if (!$artist) {
            return new JsonResponse(EntitySerialization::serialize([]), Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(EntitySerialization::serialize($artist));
    }
}
