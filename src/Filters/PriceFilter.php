<?php

namespace App\Filters;


class PriceFilter implements FilterInterface{


    private $param;
    private $em;
    private $qb;
    private $authorized = ['5','10', '15', '20'];

    public function __construct($em, $qb, $param)
    {   
        $this->em = $em;
        $this->qb = $qb;
        $this->param  = $param;    
    }


    public function process()
    {   
        if (!in_array($this->param, $this->authorized)) return;

        $this->qb
            ->andWhere('p.price < :price')
            ->setParameter('price', $this->param)
        ;
    }

    private function getTags(array $titles): Array
    {
        return $this->em->getRepository(Tag::class)->findBy(['title' => $titles]);
    }

}