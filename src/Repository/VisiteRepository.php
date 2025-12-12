<?php

namespace App\Repository;

use App\Entity\Visite;
use App\Entity\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visite::class);
    }

    /**
     * Retourne les visites d'un étudiant avec filtre statut et tri date
     */
    public function findByEtudiantWithFilterAndSort(Etudiant $etudiant, ?string $statut, ?string $sort): array
    {
        $qb = $this->createQueryBuilder('v')
            ->andWhere('v.etudiant = :etudiant')
            ->setParameter('etudiant', $etudiant);

        if ($statut && $statut !== 'toutes') {
            $qb->andWhere('v.statut = :statut')
               ->setParameter('statut', $statut);
        }

        if ($sort === 'asc') {
            $qb->orderBy('v.date', 'ASC');
        } elseif ($sort === 'desc') {
            $qb->orderBy('v.date', 'DESC');
        }

        return $qb->getQuery()->getResult();
    }
}
