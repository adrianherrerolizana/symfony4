<?php

namespace App\Services;

class MostrarMensaje
{
	private $titulo;

	public function __construct($titulo)
	{
		$this->titulo = $titulo;
	}

	public function getMensaje($tituloComparar)
    {
    	if (strpos($tituloComparar, $this->titulo) !== false)
        	return 'Has encontrado la palabra';
    	else
    		return false;
    }
}
