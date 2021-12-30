<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAll():array{
        
        $qb = $this->createQueryBuilder('a')
        ->addSelect('u')
        ->leftJoin('a.auteur','u')
        ->orderBy('a.date', 'DESC');
        return $qb->getQuery()->getResult();
    }
    public function findPagination(int $page =1,int $itemCount = 20) : Paginator{
        $begin = ($page -1)*$itemCount;
        $qb = $this->createQueryBuilder('a')
            ->setMaxResults($itemCount)
            ->setFirstResult($begin)
            ->addSelect('u')
            ->leftJoin('a.auteur','u')
            ->orderBy('a.date', 'DESC');
            return new Paginator($qb->getQuery());
    }
}
