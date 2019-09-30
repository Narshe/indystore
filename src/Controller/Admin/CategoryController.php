<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Utils\Slugger;

class CategoryController extends AbstractController
{   

    /**
     * @Route("/admin/categories", methods={"GET"}, name="admin_category_index")
     * @return Response
     */
    public function index(): Response
    {   
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }


    /**
     * @Route("/admin/categories/new", methods={"GET", "POST"}, name="admin_category_new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {  
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $category->setSlug(Slugger::slugify($category->getName()));
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/new.html.twig', [
            'categoryForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/edit/{id<\d+>}", methods={"GET", "PUT"}, name="admin_category_edit")
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function edit(Request $request, Category $category): Response
    {   

        $form = $this->createForm(CategoryType::class, $category, [
            'method' => 'PUT',
        ]);

        $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()) {
            
            
            $em = $this->getDoctrine()->getManager();
            $category->setSlug(Slugger::slugify($category->getName()));
            $category->setUpdatedAt(new \DateTime());
            $em->flush();

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'categoryForm' => $form->createView()
        ]);
    }
    
      /**
     * @Route("/admin/category/{id<\d+>}", methods={"DELETE"}, name="admin_category_destroy")
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function destroy(Request $request, Category $category): Response
    {
        $submittedToken = $request->request->get('_csrf_token');

        if ($this->isCsrfTokenValid('delete_category', $submittedToken)) {

            $em = $this->getDoctrine()->getManager();

            $em->remove($category);
            $em->flush();

        }

        return $this->redirectToRoute('admin_category_index');
    }
}
