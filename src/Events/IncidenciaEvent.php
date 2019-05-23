<?php

namespace App\Events;

use App\Entity\Incidencia;
use Symfony\Component\EventDispatcher\Event;


class IncidenciaEvent extends Event
{
	const SAVED = 'incidencia.save';
	private $incidencia;

	public function __construct(Incidencia $incidencia)
	{
		$this->incidencia = $incidencia;
	}

	public function getIssue()
	{
		return $this->incidencia;
	}
}