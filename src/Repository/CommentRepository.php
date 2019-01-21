<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
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
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllCommentsByAddress($address){
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u')
            ->addSelect('u')
            ->andWhere('c.address = :address')
            ->setParameter(':address', $address)
            ->orderBy('c.dateenvoi', 'DESC')
            ->getQuery();
        return $qb->execute();
    }

    public function findCommentUserByAddress($address, $user){
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.address = :address')
            ->setParameter(':address', $address)
            ->andWhere('c.user = :user')
            ->setParameter(':user', $user)
            ->getQuery();
        return $qb->execute();
    }

}
