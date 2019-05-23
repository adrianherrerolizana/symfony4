<?php

namespace App\Managers;

use App\Entity\Incidencia;
use Doctrine\ORM\EntityManagerInterface;

class IncidenciaManager
{

	protected $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}
	public function newObject() : Incidencia
    {
		$incidencia = new Incidencia();

		return $incidencia;
    }

	public function create($incidencia) : Incidencia
    {
		$this->em->persist($incidencia);
		$this->em->flush();

		return $incidencia;
    }

    public function update($incidencia) : Incidencia
    {
		$this->em->flush();

		return $incidencia;
    }

    public function delete($incidencia) : void
    {
		$this->em->remove($incidencia);
		$this->em->flush();

    }
}
