<?php

namespace App\Repository;

use App\Entity\Incidencia;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Incidencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Incidencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Incidencia[]    findAll()
 * @method Incidencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncidenciaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Incidencia::class);
    }

    // /**
    //  * @return Incidencia[] Returns an array of Incidencia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Incidencia
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findBySearch($titulo, $categoria, $userId)
    {
        $sql = $this->createQueryBuilder('i');
        if (!empty($titulo)) {
			$sql->andWhere('i.titulo LIKE :valTitulo')
				->setParameter('valTitulo', '%' . $titulo . '%');
		}
        if (!empty($categoria)) {
			$sql->andWhere('i.categoria = :valCategoria')
				->setParameter('valCategoria', $categoria);
		}
        if (!is_null($userId)) {
	        $sql->andWhere('i.user = :valuserId')
		        ->setParameter('valuserId', $userId);
        }
            $sql->orderBy('i.id', 'ASC');
        ;
        return $sql ->getQuery()->getResult();
    }

    public function findBySearchSoporte($titulo, $categoria, $userId)
    {
        $sql = $this->createQueryBuilder('i');
        if (!empty($titulo)) {
			$sql->andWhere('i.titulo LIKE :valTitulo')
				->setParameter('valTitulo', '%' . $titulo . '%');
		}
        if (!empty($categoria)) {
			$sql->andWhere('i.categoria = :valCategoria')
				->setParameter('valCategoria', $categoria);
		}
        if (!is_null($userId)) {
	        $sql->andWhere('i.asignada = :valasignadaId')
		        ->setParameter('valasignadaId', $userId);
        }
            $sql->orderBy('i.id', 'ASC');
        ;
        return $sql ->getQuery()->getResult();
    }

    public function findAllUser($userId)
    {

        $sql = $this->createQueryBuilder('i');
        if (!is_null($userId)) {
	        $sql->andWhere('i.user = :valuserId')
		        ->setParameter('valuserId', $userId);
        }
        $sql->orderBy('i.id', 'ASC');

        return $sql ->getQuery()->getResult();

    }

    public function findAllUserSoporte($userId)
    {

        $sql = $this->createQueryBuilder('i');
        if (!is_null($userId)) {
	        $sql->andWhere('i.asignada = :valasignadaId')
		        ->setParameter('valasignadaId', $userId);
        }
        $sql->orderBy('i.id', 'ASC');

        return $sql ->getQuery()->getResult();

    }
    public function findByLastCreated()
    {

        $sql = $this->createQueryBuilder('i');
		$sql->andWhere('i.fechaCreacion >= :valFechaCreacion')
			->setParameter('valFechaCreacion', new \DateTime('-3 day'));
        $sql->orderBy('i.fechaCreacion', 'ASC');

        return $sql ->getQuery()->getResult();

    }
    public function findByLastResolved()
    {

        $sql = $this->createQueryBuilder('i');
		$sql->andWhere('i.fechaResolucion >= :valfechaResolucion')
			->setParameter('valfechaResolucion', new \DateTime('-3 day'));
        $sql->orderBy('i.fechaResolucion', 'ASC');

        return $sql ->getQuery()->getResult();

    }
}
