<?php

namespace App\Event;

use App\Service\Tirage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControleurInterception implements EventSubscriberInterface
{
    private int $Mois;

    public function __construct(ParameterBagInterface $parametre)
    {
        $Date = new \Datetime('now');
        $this->Mois = $parametre->get('kernel.environment') != "prod" ? $Date->format('n') : 12; /*Met le mois actuel en cas de développement*/ /*phpcs:ignore Generic.Files.LineLength*/
    }

    public function onKernelController(ControllerEvent $Evenement): void
    {
        $_ENV['Mois'] = $this->Mois;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
