<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param string $category
     * @return Array
     */
    public function findAllVisible(string $category): Array
    {
        $query = $this->findVisibleQuery()
                    ->orderBy('p.created_at', 'ASC');

        if ($category !== 'all') {

            $query->innerJoin('p.category', 'c')
                ->addSelect('c.name as category_name')
                ->andWhere('c.name = :name')
                ->setParameter('name', $category)
            ;
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @param int $id
     * @return Array|null
     */
    public function findOneVisible(int $id): ?Array
    {
        return $this->findVisibleQuery()
            ->andwhere("p.id = {$id}")
            ->innerJoin('p.category', 'c')
            ->addSelect('c.name as category_name')
            ->getQuery()
            ->getOneOrNullResult()
        ;
   
    } 
    /*
    public function findAllVisibleProductWithCategories(string $category)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->select('p.id, p.name, p.description, p.price')
            ->orderBy('p.created_at', 'ASC')
            ->andwhere('p.visible = 1')
            ->andWhere('c.name = :name')
            ->setParameter('name', $category)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @param Array $ids
     * @return Array
     */
    public function findInArray(array $ids): Array
    {
        $qb = $this->findVisibleQuery();

        $qb->andWhere($qb->expr()->in('p.id', $ids));

        return $qb->getQuery()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    private function findVisibleQuery(): QueryBuilder {

        return $this->createQueryBuilder('p')
            ->select('p.id, p.name, p.description, p.price')
            ->andwhere('p.visible = 1')
        ;
    }


}
