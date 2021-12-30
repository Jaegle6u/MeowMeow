<?php

namespace App\Repository;

use App\Entity\Cat;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cat[]    findAll()
 * @method Cat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cat::class);
    }

    public function findAll():array{
        /**
         * SELECT c.*,i.*,u.* FROM cat AS c
         * LEFT JOIN image AS i ON i.id = c.image_id
         * ...
         */
        $qb = $this->createQueryBuilder('c')
        ->addSelect('i,u')
        ->leftJoin('c.image','i')
        ->leftJoin('c.user','u');
        return $qb->getQuery()->getResult();
    }
    public function findPagination(int $page =1,int $itemCount = 20) : Paginator{
        $begin = ($page -1)*$itemCount;
        $qb = $this->createQueryBuilder('c')
            ->setMaxResults($itemCount)
            ->setFirstResult($begin);
            $this->addJoin($qb);
            return new Paginator($qb->getQuery());
    }

    public function findUser(User $user){
        $qb = $this-> createQueryBuilder('c')
        ->addSelect('i,u')
        ->leftJoin('c.image','i')
        ->leftJoin('c.user','u')
        ->where('c.user = :user')
        ->setParameter(':user',$user);
        return $qb->getQuery()->getResult();
    }

    public function findEnabled(): array 
    {
        $qb = $this->createQueryBuilder('c');

        $this->addJoin($qb);
        $qb->where('c.publish = true');

        return $qb->getQuery()->getResult();
    }
    public function findBestCat()
    {
        $qb = $this->createQueryBuilder('c')
            ->addSelect('COUNT(l) as like_count')
            ->leftJoin('c.likes', 'l')
            ->groupBy("c.id")
            ->setMaxResults(1)
            ->orderBy('like_count', 'desc')
        ;
      return $qb->getQuery()->getOneOrNullResult();
    }
    public function classement()
    {
        $qb = $this->createQueryBuilder('c')
            ->addSelect('COUNT(l) as like_count')
            ->leftJoin('c.likes', 'l')
            ->groupBy("c.id")
            
            ->orderBy('like_count', 'desc')
        ;
      return $qb->getQuery()->getResult();
    }

    private function addJoin(QueryBuilder $qb): void 
    {
        $qb->addSelect('i, u')
            ->leftJoin('c.image', 'i')
            ->leftJoin('c.user', 'u')
        ;
    }

    // /**
    //  * @return Cat[] Returns an array of Cat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
