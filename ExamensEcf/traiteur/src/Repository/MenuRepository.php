<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    //    /**
    //     * @return Menu[] Returns an array of Menu objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Menu
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findByFilters(?string $theme, ?string $regime): array
{
    $qb = $this->createQueryBuilder('m')
        ->leftJoin('m.theme', 't')
        ->leftJoin('m.regimes', 'r')
        ->addSelect('t', 'r')
        ->setMaxResults(50);

    if ($theme) {
        $qb->andWhere('t.id = :theme')
           ->setParameter('theme', $theme);
    }

    if ($regime) {
        $qb->andWhere('r.id = :regime')
           ->setParameter('regime', $regime);
    }

    return $qb->getQuery()->getResult();
}
}
