<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    /**
     * @Route("/games", methods={"GET"}, name="games_index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {   
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();


        if ($request->isXmlHttpRequest()) {
            return $this->json(['games' => $products]);
        }
        
        return $this->render('product/index.html.twig', [
            'games' => $products,
        ]);
    }

        /**
     * @Route("/games/{id<\d+>}", methods={"GET"}, name="admin_game_show")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function show(Request $request, Product $product): Response
    {

        if ($request->isXmlHttpRequest()) {
            return $this->json(['game' => $product]);
        }

        return $this->render('product/show.html.twig', [
            'game' => $product
        ]);
    }
}
