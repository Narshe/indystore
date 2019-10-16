<?php

namespace App\Filters;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;


class Filter {


    private $request;
    protected $qb;
    protected $em;

    public function __construct(array $requestStack, QueryBuilder $qb, EntityManager $em)
    {
       $this->request = $requestStack;
       $this->qb = $qb;
       $this->em = $em;
    }


    public function run()
    {
    
        foreach($this->request as $key => $param) {

            $filter = $this->getClassName($key);

            if(class_exists($filter)) {
                
                $filter = new $filter($this->em, $this->qb, $param);
                $filter->process();
            }
        }
    }

    public function getClassName($name)
    {
        return __NAMESPACE__.'\\'.ucfirst($name).'Filter';
    }

    public function getFilteredQuery()
    {
        return $this->qb;
    }


}


