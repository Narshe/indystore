<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use App\Filters\Filter;
use Doctrine\ORM\Query;

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
     * @param Array $params
     * @return Array
     */
    public function findAllVisibleQuery(array $params): Query
    {
        $query = $this->findVisibleQuery()
                    ->orderBy('pde.releaseDate', 'DESC')
        ;
        
        $filters = new Filter($params, $query, $this->getEntityManager());
        $filters->run();

        return $filters->getFilteredQuery()->getQuery();
    }

    /**
     * @param int $id
     * @return Array|null
     */
    public function findOneVisible(int $id): ?Product
    {
        return $this->findVisibleQuery()
            ->andwhere("p.id = :id")
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Array
     */
    public function findTopSellProducts(): Array
    {

        return $this->findVisibleQuery()
            ->orderBy('pde.soldNumber', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string $option new|soon
     * @return Array
     */
    public function findProductsDateInterval(string $option): Array
    {
        $qb = $this->findVisibleQuery();

        return $qb
            ->andWhere($qb->expr()->between('pde.releaseDate', ':date1', ':date2'))
            ->setParameter('date1', $option === 'new' ? new \DateTime('-1 MONTH') : new \DateTime())
            ->setParameter('date2', $option === 'new' ? new \DateTime() : new \DateTime('+1 MONTH'))
            ->getQuery()
            ->getResult()
        ;
    }

      /**
     * @param int|null $limit
     * @return Array
     */
    public function findDiscountedProduct(?int $limit = null): Array
    {    
        return $this->findVisibleQuery()
            ->addSelect('d.amount')
            ->andWhere('pde.discount > 0')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @param Array $ids
     * @return Array
     */
    public function findInArray(array $ids): Array
    {
        $qb = $this->findVisibleQuery();

        return $qb
            ->andWhere($qb->expr()->in('p.id', $ids))
            ->getQuery()
            ->getResult()
        ;

    }

    /**
     * @return QueryBuilder
     */
    private function findVisibleQuery(): QueryBuilder {

        return $this->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->join('p.category', 'c')
            ->innerjoin('p.product_detail', 'pde')
            ->leftjoin('pde.discount', 'd')
            ->addSelect('pde, d, t, c')
            ->andwhere('p.visible = 1')
        ;

    }


}
