<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', []);
    }

    /**
     * @Route("/api/listRegions", name="list_regions")
     */
    public function listRegionsAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $regions = $em->getRepository(Region::class)->findAll();

        $tabRegions = [];
        foreach ($regions as $region)
        {
            /**
             * @var Region $region
             */
            $tabRegions[] = $region->toArray();
        }

        return new JsonResponse($tabRegions);
    }
}
