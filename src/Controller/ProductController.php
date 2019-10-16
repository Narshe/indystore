<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;


use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Tag;
use App\Filters\Filter;

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

        $doctrine = $this->getDoctrine();

        $tagList = $doctrine->getRepository(Tag::class)->findTagsName();

        $products = $doctrine->getRepository(Product::class)->findAllVisible($request->query->all());

        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'games' => $products,
                'taglist' => $tagList
            ]);
        }
        
        return $this->render('product/index.html.twig', [
            'games' => $products,
            'taglist' => $tagList
        ]);
    }

        /**
     * @Route("/games/{id<\d+>}", methods={"GET"}, name="games_show")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function show(Request $request, int $id): Response
    {   
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneVisible($id);

        if ($request->isXmlHttpRequest()) {
            return $this->json(['game' => $product]);
        }

        return $this->render('product/show.html.twig', [
            'game' => $product
        ]);
    }
}
