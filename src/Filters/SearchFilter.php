<?php

namespace App\Filters;
use App\Entity\Tag;

class SearchFilter implements FilterInterface{


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
        $titles = explode(',', str_replace(' ', '', $this->param));

        foreach($this->getTags($titles) as $key => $t) {
            $this->qb
                ->andWhere($this->qb->expr()->isMemberOf(":tag{$key}", 'p.tags'))
                ->setParameter("tag{$key}", $t->getId())
            ;
        }
    }

    private function getTags(array $titles): Array
    {
        return $this->em->getRepository(Tag::class)->findBy(['title' => $titles]);
    }
}