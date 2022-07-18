<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Verification
  {
  private int $Cas;

  public function __construct(ContainerBagInterface $parametre)
    {
    $this->Cas = 0;

    try
      {
      $parametre->get('Titre');
      $parametre->get('TitreCupidon');
      $parametre->get('TexteCupidon');
      $parametre->get('TitrePoisson');
      $parametre->get('TextePoisson');
      $parametre->get('TitreCadeau');
      $parametre->get('TexteCadeau');
      $parametre->get('CouleurFond');
      $parametre->get('CouleurTexte');
      $parametre->get('Noel');
      $parametre->get('Neige');
      $parametre->get('Forme');
      $parametre->get('Style');
      $parametre->get('Taille');
      $parametre->get('Pot2Miel');
      $parametre->get('Resultats');
      }
    catch (\Exception $Pb)
      {
      $this->Cas = 2;
      }

    if (!file_exists('../.env.local'))
      {
      $this->Cas = 1;
      }
    }

  public function isValide(): bool
    {
    return $this->Cas === 0 ? true : false ;
    }

  public function getErreur(): string
    {
    switch ($this->Cas)
      {
      case 1:
        return 'Information.Configuration.Fichier';
      case 2:
        return 'Information.Configuration.Variable';
      default:
        return "";
      }
    }
  }
