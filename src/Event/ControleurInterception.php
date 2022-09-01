<?php

namespace App\Event;

use App\Service\Tirage;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControleurInterception implements EventSubscriberInterface
  {
  private int $Mois;
  private array $Resultats;

  public function __construct(ContainerBagInterface $parametre)
    {
    $Date = new \Datetime('now');
    $this->Mois = $parametre->get('kernel.environment') == "dev" ? $Date->format('n') : 12; //Pour mettre le mois actuel en cas de développement

    $Tirage = new Tirage();
    $this->Resultats= $Tirage->getResultats();
    }

  public function onKernelController(ControllerEvent $Evenement)
    {
    $_ENV['Mois'] = $this->Mois;
    $_ENV['Resultats'] = $this->Resultats;
    }

  public static function getSubscribedEvents()
    {
    return [
           KernelEvents::CONTROLLER => 'onKernelController',
           ];
    }
  }