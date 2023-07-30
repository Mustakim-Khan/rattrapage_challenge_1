<?php

namespace App\Repository;

use App\Entity\TasksList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TasksList>
 *
 * @method TasksList|null find($id, $lockMode = null, $lockVersion = null)
 * @method TasksList|null findOneBy(array $criteria, array $orderBy = null)
 * @method TasksList[]    findAll()
 * @method TasksList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasksListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TasksList::class);
    }

//    /**
//     * @return TasksList[] Returns an array of TasksList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TasksList
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
