<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;
use App\ProductDetail;

class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="home")
     * @return Response
     */
    public function index(): Response
    {   
        $productRepository = $this->getDoctrine()->getRepository(Product::class);

        $topSellGames = $productRepository->findTopSellProducts();
        $newGames = $productRepository->findProductsDateInterval('new');
        $comingSoonGames = $productRepository->findProductsDateInterval('soon');

     //   dd($topSellGames, $newGames, $comingSoonGames);
        return $this->render('page/index.html.twig', [
            'topSellGames' => $topSellGames,
            'newGames' => $newGames,
            'comingSoonGames' => $comingSoonGames
        ]);
    }
}
