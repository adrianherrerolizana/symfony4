<?php

namespace App\Command;

use App\Managers\IncidenciaManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class IncidenciaCommand extends Command
{
	private $incidenciaManager;
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:nueva_incidencia';

    public function __construct(IncidenciaManager $incidenciaManager)
	{
		$this->incidenciaManager = $incidenciaManager;
		parent::__construct();
	}

	protected function configure(){

	}

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Título incidencia: ', false);

        $titulo = $helper->ask($input, $output, $question);

        $question = new Question('Descripción incidencia: ', false);

        $descripcion = $helper->ask($input, $output, $question);

        $question = new ChoiceQuestion(
			'¿Resuelta?',
			['yes', 'no'],
			0
		);
		$question->setErrorMessage('No válido.');
        $resuelta = $helper->ask($input, $output, $question);

        $question = new Question('Fecha resolución: ', false);

        $fechaResolucion = $helper->ask($input, $output, $question);

        $incidencia = $this->incidenciaManager->newObject();

        $incidencia->setTitulo($titulo);
        $incidencia->setDescripcion($descripcion);
        $incidencia->setFechaCreacion(new \DateTime());
        $incidencia->setFechaResolucion(new \DateTime($fechaResolucion));
        if ($resuelta == 'yes')
        	$incidencia->setResuelta(1);
        else
        	$incidencia->setResuelta(0);

        $this->incidenciaManager->create($incidencia);
        $output->writeln('Título escogido: '.$titulo);
        $output->writeln('Descripción escogido: '.$descripcion);
        $output->writeln('Resuelta: '.$resuelta);
        $output->writeln('Fecha resolución: '.date('Y-m-d', strtotime($fechaResolucion)));

    }
}
