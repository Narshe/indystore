<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Product;
use App\Entity\Cart;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", methods={"GET"}, name="cart_index")
     * @return Response
     */
    public function index(SessionInterface $session): Response
    {
        $cart = $session->get('cart') ?: new ArrayCollection();

        $products = !$cart->isEmpty() ? $this->getDoctrine()->getRepository(Product::class)->findInArray($cart->getKeys()) : [];

        $c = new Cart($cart);

        return $this->render('cart/index.html.twig', [
            'games' => $products,
            'cart' => $c
        ]);
    }

    /**
     * @Route("/cart/add/{id<\d+>}", methods={"POST"}, name="cart_add")
     * @param Request $request
     * @param SessionInterface $session
     * @param Product $product
     * @return Response
     */
    public function add(Request $request, SessionInterface $session, Product $product): Response
    {   
        $productId = $product->getId();

        $cart = $session->get('cart') ?: new ArrayCollection();
        $c = new Cart($cart);
        $c->addProduct($productId);

        $session->set('cart', $c->getCart());

        return $this->redirectToRoute('cart_index');
    } 

    /**
     * @Route("/cart/remove/{id<\d+>}", methods={"POST"}, name="cart_remove")
     * @param Request $request
     * @param SessionInterface $session
     * @param Product $product
     * @return Response
     */
    public function remove(Request $request, SessionInterface $session, Product $product) 
    {
        $productId = $product->getId();

        $cart = $session->get('cart') ?: new ArrayCollection();
        $c = new Cart($cart);
        $c->removeProduct($productId);

        $session->set('cart', $c->getCart());

        return $this->redirectToRoute('cart_index');
    }

     /**
     * @Route("/cart/destroy", methods={"DELETE"}, name="cart_destroy")
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function destroy(Request $request, SessionInterface $session) 
    {

        if ($session->get('cart')) {
            $session->set('cart', new ArrayCollection());
        }

        return $this->redirectToRoute('cart_index');
    }
}
