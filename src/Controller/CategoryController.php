<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_index")
     */
    public function index()
    {   
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
