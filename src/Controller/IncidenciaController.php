<?php

namespace App\Controller;

use App\Entity\Incidencia;
use App\Entity\User;
use App\Events\IncidenciaEvent;
use App\Form\IncidenciaSearchType;
use App\Form\IncidenciaType;
use App\Repository\IncidenciaRepository;
use App\Services\FileUploader;
use App\Services\GenerarCodigo;
use App\Services\MostrarMensaje;
use App\Managers\IncidenciaManager;
use Knp\Component\Pager\PaginatorInterface;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @Route("/incidencia")
 */

class IncidenciaController extends AbstractController
{
    /**
     * @Route("/", name="incidencia_index")
     */
    public function index(Request $request, IncidenciaRepository $incidenciaRepository, PaginatorInterface $paginator): Response
    {
    	$formSearch = $this->createForm(IncidenciaSearchType::class);
    	$formSearch->handleRequest($request);

    	$user = $this->getUser();

    	if($formSearch->isSubmitted()){

    		$categoriaSearch = $formSearch->getData()['categoriaSearch'];
    		$tituloSearch = $formSearch->getData()['tituloSearch'];

    		$busqueda = 1;
    		$userSearch = null;

            if ($this->isGranted('ROLE_COMERCIAL')) {

    			$userSearch = $user;

            } elseif ($this->isGranted('ROLE_SOPORTE')) {

    			$userSearch = $user;
                $busqueda = 2;

            }

    		if ($busqueda == 1)
    		    $incidencias = $incidenciaRepository->findBySearch($tituloSearch, $categoriaSearch, $userSearch);
    		else
    		    $incidencias = $incidenciaRepository->findBySearchSoporte($tituloSearch, $categoriaSearch, $userSearch);

		}else {

    		$busqueda = 1;
    		$userSearch = null;

    		if ($this->isGranted('ROLE_COMERCIAL')) {

    			$userSearch = $user;

            } elseif ($this->isGranted('ROLE_SOPORTE')) {

    			$userSearch = $user;
                $busqueda = 2;

            }

    		if ($busqueda == 1)
		        $incidencias = $incidenciaRepository->findAllUser($userSearch);
    		else
		        $incidencias = $incidenciaRepository->findAllUserSoporte($userSearch);
	    }

    	// Paginate the r   esults of the query
        $incidenciasPaginadas = $paginator->paginate(
            // Doctrine Query, not results
            $incidencias,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        return $this->render('incidencia/index.html.twig', [
            'incidencias' => $incidenciasPaginadas,
			'form' => $formSearch->createView(),
        ]);
    }


    /**
     * @Route("/new", name="incidencia_new", methods={"GET","POST"})
     */
    public function new(Request $request, IncidenciaManager $incidenciaManager, EventDispatcherInterface $dispacher, GenerarCodigo $generarCodigo, FileUploader $fileUploader): Response
    {
        $incidencia = $incidenciaManager->newObject();

        $form = $this->createForm(IncidenciaType::class, $incidencia);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

			$file = $form['urlImagen']->getData();

	        $filename = $fileUploader->upload($file);

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
     * @Route("/solved.json", name="issue_set_solved", methods={"POST"})
     */
    public function solved(Request $request, IncidenciaManager $incidenciaManager): Response
    {
        $issueId = $request->get('id');
        $incidencia = $this->getDoctrine()->getRepository(Incidencia::class)->find($issueId);

        $this->denyAccessUnlessGranted('edit', $incidencia);

        $incidenciaManager->setSolved($incidencia);

        return new JsonResponse(
            $this->renderView(
                'incidencia/incidencia.html.twig',
                array('incidencia' => $incidencia)
            )
        );
    }

    /**
     * @Route("/filterLastCreated", name="incidencia_filterLastCreated")
     */
    public function filterLastCreated(Request $request, IncidenciaRepository $incidenciaRepository): Response
    {

        $incidencias = $incidenciaRepository->findByLastCreated();

        $formSearch = $this->createForm(IncidenciaSearchType::class);
    	$formSearch->handleRequest($request);

        return $this->render('incidencia/index.html.twig', [
            'incidencias' => $incidencias,
	        'form' => $formSearch->createView(),
        ]);

    }

    /**
     * @Route("/filterLastResolved", name="incidencia_filterLastResolved")
     */
    public function filterLastResolved(Request $request, IncidenciaRepository $incidenciaRepository): Response
    {
        $incidencias = $incidenciaRepository->findByLastResolved();

        $formSearch = $this->createForm(IncidenciaSearchType::class);
    	$formSearch->handleRequest($request);

        return $this->render('incidencia/index.html.twig', [
            'incidencias' => $incidencias,
	        'form' => $formSearch->createView(),
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
    public function edit(Request $request, Incidencia $incidencia, IncidenciaManager $incidenciaManager, FileUploader $fileUploader): Response
    {
    	$this->denyAccessUnlessGranted('view', $incidencia);
        $form = $this->createForm(IncidenciaType::class, $incidencia);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            if ($form->isSubmitted()) {
                if ($form->isValid()) {

                    $file = $form['urlImagen']->getData();

                    if (isset($file)) {

                        $filename = $fileUploader->upload($file);

                        $incidencia->setUrlImagen($filename);

                    }

                    $issue = $incidenciaManager->update($incidencia);

                    return $this->render(
                        'incidencia/incidencia.html.twig',
                        array(
                            'incidencia' => $issue,
                        )
                    );
                }
            }

        } else {
            if ($form->isSubmitted()) {
                if ($form->isValid()) {

                    $file = $form['urlImagen']->getData();

                    if (isset($file)) {

                        $filename = $fileUploader->upload($file);

                        $incidencia->setUrlImagen($filename);

                    }

                    $issue = $incidenciaManager->update($incidencia);

                    $this->addFlash(
                        'success',
                        'Se ha modificado correctamente'
                    );

                    $this->addFlash('success', 'Creado correctamente!');

                    return $this->redirectToRoute('incidencia_show', [
                        'id' => $incidencia->getId(),
                    ]);
                } else {
                    $this->addFlash(
                        'notice',
                        'Se han producido errores, revise el formulario.'
                    );
                }
            }

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
    	$this->denyAccessUnlessGranted('view', $incidencia);
        //if ($this->isCsrfTokenValid('delete'.$incidencia->getId(), $request->request->get('_token')))
            $incidenciaManager->delete($incidencia);

        return $this->redirectToRoute('incidencia_index');
    }

}
