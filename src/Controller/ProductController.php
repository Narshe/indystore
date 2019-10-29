<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use App\Collections\ProductCollection;
use App\Entity\Product;
use App\Entity\Tag;
use App\Serializer\Normalizer\ProductNormalizer;

class ProductController extends AbstractController
{
    /**
     * @Route("/games", methods={"GET"}, name="games_index")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param PaginatorInterface $paginator
     * @param ProductNormalizer $normalizer
     * @return Response
     */
    public function index(Request $request, SerializerInterface $serializer, PaginatorInterface $paginator, ProductNormalizer $normalizer): Response
    {  
        $doctrine = $this->getDoctrine();
        $tagList = $doctrine->getRepository(Tag::class)->findTagsName();

        $query = $doctrine->getRepository(Product::class)->findAllVisibleQuery($request->query->all());

        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            25
        );

        if ($request->isXmlHttpRequest()) {

            $serializer = new Serializer([$normalizer], [new JsonEncoder()]);
            
            $products = $serializer->normalize($products);
            
            $response = new Response();
            $response->setContent(json_encode([
                'products' => $products,
                'tagList' => $tagList
            ]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        
        return $this->render('product/index.html.twig', [
            'games' => $products,
            'taglist' => $tagList
        ]);
    }

        /**
     * @Route("/games/{id<\d+>}", methods={"GET"}, name="games_show")
     * @param Request $request
     * @param int $id
     * @param SerializerInterface $serializer
     * @param ProductNormalizer $normalizer
     * @return Response
     */
    public function show(Request $request, int $id, SerializerInterface $serializer, ProductNormalizer $normalizer): Response
    {   
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneVisible($id);
        
        if(!$product) {
            throw new NotFoundHttpException("404");
        }

        if ($request->isXmlHttpRequest()) {

            $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

            $product = $serializer->normalize($product, 'json');

            $response = new Response();
            $response->setContent(json_encode([
                'products' => $product,
            ]));

            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        return $this->render('product/show.html.twig', [
            'game' => $product
        ]);
    }
}
