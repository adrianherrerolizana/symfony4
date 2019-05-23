<?php

namespace App\Services;

use App\Entity\Incidencia;

class GenerarCodigo
{
	private $incidencia;

	public function __construct()
	{

	}

	public function generarCodigo(Incidencia $incidencia)
    {
		$incidencia->setCodigoIncidencia($incidencia->getId().'|'.date('Y'));

		return $incidencia;
    }
}
