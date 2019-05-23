<?php

namespace App\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class FechaRunTime implements RuntimeExtensionInterface
{
	public function fechaFormato($fecha, $formato = 'd/m/Y'){

		return $fecha->format($formato);

	}
}