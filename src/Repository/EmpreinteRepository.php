<?php

namespace App\Repository;

use App\Entity\Empreinte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Empreinte>
 *
 * @method Empreinte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Empreinte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Empreinte[]    findAll()
 * @method Empreinte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpreinteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Empreinte::class);
    }
   

//    /**
//     * @return Empreinte[] Returns an array of Empreinte objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Empreinte
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
