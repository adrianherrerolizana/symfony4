<?php

namespace App\Controller;

use App\Entity\Incidencia;
use App\Events\IncidenciaEvent;
use App\Form\IncidenciaSearchType;
use App\Form\IncidenciaType;
use App\Repository\IncidenciaRepository;
use App\Services\GenerarCodigo;
use App\Services\MostrarMensaje;
use App\Managers\IncidenciaManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/incidencia")
 */

class IncidenciaController extends AbstractController
{
    /**
     * @Route("/", name="incidencia_index")
     */
    public function index(Request $request, IncidenciaRepository $incidenciaRepository): Response
    {
    	$formSearch = $this->createForm(IncidenciaSearchType::class);
    	$formSearch->handleRequest($request);

    	if($formSearch->isSubmitted()){
    		$categoriaSearch = $formSearch->getData()['categoriaSearch'];
    		$tituloSearch = $formSearch->getData()['tituloSearch'];
    		$incidencias = $incidenciaRepository->findBySearch($tituloSearch, $categoriaSearch);
		}else
			$incidencias = $incidenciaRepository->findAll();
        return $this->render('incidencia/index.html.twig', [
            'incidencias' => $incidencias,
			'form' => $formSearch->createView(),
        ]);
    }


    /**
     * @Route("/new", name="incidencia_new", methods={"GET","POST"})
     */
    public function new(Request $request, IncidenciaManager $incidenciaManager, EventDispatcherInterface $dispacher, GenerarCodigo $generarCodigo): Response
    {
        $incidencia = $incidenciaManager->newObject();

        $form = $this->createForm(IncidenciaType::class, $incidencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

			$file = $form['urlImagen']->getData();
			$filename = md5(uniqid()).'.'.$file->guessExtension();
			$file->move('uploads/documents', $filename);

			$incidencia->setUrlImagen($filename);

        	$incidenciaManager->create($incidencia);

        	$incidencia = $generarCodigo->generarCodigo($incidencia);

        	$incidenciaManager->update($incidencia);

        	$event = new IncidenciaEvent($incidencia);
			$dispacher->dispatch(IncidenciaEvent::SAVED, $event);

            $this->addFlash('success', 'Creado correctamente!');

            return $this->redirectToRoute('incidencia_show',['id' => $incidencia->getId()]);
        }

        return $this->render('incidencia/create.html.twig', [
            'incidencia' => $incidencia,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="incidencia_show", methods={"GET"})
     */
    public function show(Incidencia $incidencia, MostrarMensaje $mostrarMensaje): Response
    {
        $this->denyAccessUnlessGranted('view', $incidencia);
    	$mensaje = $mostrarMensaje->getMensaje($incidencia->getTitulo());
		(($mensaje) ? $this->addFlash('notice', $mensaje) : '' );
        return $this->render('incidencia/show.html.twig', [
            'incidencia' => $incidencia
        ]);
    }

    /**
     * @Route("/{id}/edit", name="incidencia_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Incidencia $incidencia, IncidenciaManager $incidenciaManager): Response
    {
        $form = $this->createForm(IncidenciaType::class, $incidencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incidenciaManager->update($incidencia);

            $this->addFlash('success', 'Creado correctamente!');

            return $this->redirectToRoute('incidencia_show', [
                'id' => $incidencia->getId(),
            ]);
        }

        return $this->render('incidencia/edit.html.twig', [
            'incidencia' => $incidencia,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="incidencia_delete", methods={"DELETE", "GET"})
     */
    public function delete(Request $request, Incidencia $incidencia, IncidenciaManager $incidenciaManager): Response
    {
        //if ($this->isCsrfTokenValid('delete'.$incidencia->getId(), $request->request->get('_token')))
            $incidenciaManager->delete($incidencia);

        return $this->redirectToRoute('incidencia_index');
    }
}