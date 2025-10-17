<?php

namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    public function findAllEmpruntById (int $idUser){
            $entityManager = $this->getEntityManager();

            $query = $entityManager->createQuery(
                'SELECT COUNT(e.id) AS nombre_emprunts
                FROM App\Entity\Emprunt e
                WHERE e.utilistateur_id = :idUser
                AND e.date_retour IS NULL'
            )->setParameter('idUser', $idUser);

            return (int) $query->getSingleScalarResult();
    }

    //    
}
