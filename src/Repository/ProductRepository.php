<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

use App\Filters\Filter;


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
    public function findAllVisible(array $params): Array
    {
        $query = $this->findVisibleQuery()
                    ->orderBy('p.created_at', 'ASC');

        $filters = new Filter($params, $query, $this->getEntityManager());
        $filters->run();

        return $filters->getFilteredQuery()->getQuery()->getResult();
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

    /**
     * @return Array
     */
    public function findTopSellProducts(): Array
    {

        return $this->findVisibleQuery()
            ->innerjoin('p.product_detail', 'product_detail')
            ->orderBy('product_detail.soldNumber', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $option new|soon
     * @return Array
     */
    public function findProductsDateInterval(string $option): Array
    {

        $qb = $this->findVisibleQuery();
        
        return $qb
            ->innerjoin('p.product_detail', 'pde')
            ->andWhere($qb->expr()->between('pde.releaseDate', ':date1', ':date2'))
            ->setParameter('date1', $option === 'new' ? new \DateTime('-1 MONTH') : new \DateTime())
            ->setParameter('date2', $option === 'new' ? new \DateTime() : new \DateTime('+1 MONTH'))
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
