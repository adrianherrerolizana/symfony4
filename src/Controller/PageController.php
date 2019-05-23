<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
	/**
	 * @Route("/primera-pagina-anotaciones")
	 */
	public function index()
	{
//		return new Response(
//			'<html><body>Hola Mundo</body></html>'
//		);
		$name = 'Adrián';
		return $this->render('page/index.html.twig',
			array('name' => $name)
		);

	}
}