<?php

namespace App\Filters;

use App\Entity\Category;

class CategoryFilter implements FilterInterface{


    private $param;
    private $em;
    private $qb;

    public function __construct($em, $qb, $param)
    {   
        $this->em = $em;
        $this->qb = $qb;
        $this->param  = $param;    
    }


    public function process()
    {   
        $category = $this->getCategory($this->param);
        
        if ($this->param !== 'all' && $category) {

            $this->qb->innerJoin('p.category', 'c')
                ->addSelect('c.name as category_name')
                ->andWhere('c.name = :name')
                ->setParameter('name', $category->getName())
            ;
        }
    }

    private function getCategory(string $category): Category
    {
        return $this->em->getRepository(Category::class)->findOneBy(['name' => $category]);
    }
}