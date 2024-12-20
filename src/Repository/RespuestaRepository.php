<?php

namespace App\Repository;

use App\Entity\Respuesta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Respuesta>
 */
class RespuestaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Respuesta::class);
    }

    //    /**
    //     * @return Respuesta[] Returns an array of Respuesta objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Respuesta
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function obtenerConteoPorPregunta(int $preguntaId): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT respuesta_elegida, COUNT(*) as total
        FROM respuesta
        WHERE pregunta_id = :preguntaId
        GROUP BY respuesta_elegida
    ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['preguntaId' => $preguntaId]);

        return $resultSet->fetchAllAssociative();
    }
}
