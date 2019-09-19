<?php

namespace App\EventsSubscribers;

use App\Events\IncidenciaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IncidenciaSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [

			IncidenciaEvent::SAVED => 'sendMail',

		];
	}

	public function sendMail(IncidenciaEvent $event)
	{

		 /*
        $issue = $event->getIssue();
        if ($issue->getEmail()) {
            $message = (new \Swift_Message('Incidencia registrada.'))
                ->setFrom('labarta.david@gmail.com')
                ->setTo($issue->getEmail())
                ->setBody(
                    $this->twig->render(
                        'emails/issue.html.twig',
                        ['issue' => $issue]
                    ),
                    'text/html'
                );
            $this->mailer->send($message);
        }
        */

	}
}