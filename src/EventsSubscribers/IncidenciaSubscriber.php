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

	}
}