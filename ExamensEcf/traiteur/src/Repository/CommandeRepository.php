<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    //    /**
    //     * @return Commande[] Returns an array of Commande objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByFiltersCA(?string $menuId, ?string $dateDebut, ?string $dateFin): array
{
    $qb = $this->createQueryBuilder('c')
        ->leftJoin('c.menu', 'm')
        ->addSelect('m')
        ->where('c.statut != :annulee')
        ->setParameter('annulee', 'annulee');

    if ($menuId) {
        $qb->andWhere('m.id = :menuId')
           ->setParameter('menuId', $menuId);
    }

    if ($dateDebut) {
        $qb->andWhere('c.date_commande >= :dateDebut')
           ->setParameter('dateDebut', new \DateTime($dateDebut));
    }

    if ($dateFin) {
        $qb->andWhere('c.date_commande <= :dateFin')
           ->setParameter('dateFin', new \DateTime($dateFin));
    }

    return $qb->getQuery()->getResult();
}

}
