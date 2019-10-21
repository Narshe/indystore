<?php

namespace App\Controller\Admin;

use App\Entity\Discount;
use App\Form\DiscountType;
use App\Repository\DiscountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/discounts")
 */
class DiscountController extends AbstractController
{
    /**
     * @Route("/", name="admin_discount_index", methods={"GET"})
     */
    public function index(DiscountRepository $discountRepository): Response
    {
        return $this->render('admin/discount/index.html.twig', [
            'discounts' => $discountRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_discount_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $discount = new Discount();
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($discount);
            $entityManager->flush();

            return $this->redirectToRoute('admin_discount_index');
        }

        return $this->render('admin/discount/new.html.twig', [
            'discount' => $discount,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_discount_show", methods={"GET"})
     */
    public function show(Discount $discount): Response
    {
        return $this->render('admin/discount/show.html.twig', [
            'discount' => $discount,
        ]);
    }

    /**
     * @Route("/{id<\d+>}/edit", name="admin_discount_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Discount $discount): Response
    {
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_discount_index');
        }

        return $this->render('admin/discount/edit.html.twig', [
            'discount' => $discount,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id<\d+>}", name="admin_discount_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Discount $discount): Response
    {
        if ($this->isCsrfTokenValid('delete'.$discount->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($discount);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_discount_index');
    }
}
