<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;
use App\ProductDetail;
use App\Repository\ProductRepository;

class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="home")
     * @return Response
     */
    public function index(ProductRepository $productRepository): Response
    {   
        $discountLimit = 8;

        $topSellGames = $productRepository->findTopSellProducts();
        $newGames = $productRepository->findProductsDateInterval('new');
        $comingSoonGames = $productRepository->findProductsDateInterval('soon');
        $discountedGames = $productRepository->findDiscountedProduct($discountLimit);

        return $this->render('page/index.html.twig', [
            'topSellGames' => $topSellGames,
            'newGames' => $newGames,
            'comingSoonGames' => $comingSoonGames,
            'discountedGames' => $discountedGames
        ]);
    }
}
