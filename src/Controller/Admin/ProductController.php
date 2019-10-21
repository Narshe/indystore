<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Product;
use App\Entity\ProductDetail;
use App\Form\ProductDetailType;
use App\Form\ProductType;

class ProductController extends AbstractController
{   

    /**
     * @Route("/admin/games", methods={"GET"}, name="admin_game_index")
     * @return Response
     */
    public function index(): Response
    {   
        $games = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('admin/product/index.html.twig', [
            'games' => $games,
        ]);
    }


    /**
     * @Route("/admin/games/new", name="admin_game_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {  
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_game_index');
        }

        return $this->render('admin/product/new.html.twig', [
            'productForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/games/edit/{id<\d+>}", methods={"GET", "PUT"}, name="admin_game_edit")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function edit(Request $request, Product $product): Response
    {   

        $form = $this->createForm(ProductType::class, $product, [
            'method' => 'PUT',
        ]);
        
        $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $product->setUpdatedAt(new \DateTime());
            $em->flush();

            return $this->redirectToRoute('admin_game_index');
        }

        return $this->render('admin/product/edit.html.twig', [
            'productForm' => $form->createView()
        ]);
    }
    
      /**
     * @Route("/admin/games/{id<\d+>}", methods={"DELETE"}, name="admin_game_destroy")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function destroy(Request $request, Product $product): Response
    {
        $submittedToken = $request->request->get('_csrf_token');

        if ($this->isCsrfTokenValid('delete_product', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();

            $em->remove($product);
            $em->flush();

        }

        return $this->redirectToRoute('admin_game_index');
    }
}
