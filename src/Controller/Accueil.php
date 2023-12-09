<?php

namespace App\Controller;

use App\Service\Verification;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Accueil extends AbstractController
  {
  #[Route('/', name: 'Accueil', methods: ['GET'])]
  public function Accueil(): Response
    {
    $Verification = new Verification($this->container->get('parameter_bag'));
    if (!$Verification->isValide())
      {
      return $this->render('message/information.html.twig',
          [
          'Indentation' => '  ',
          'Message' => $Verification->getErreur()
          ]
        );
      }

    $Date = new \Datetime('now');
    switch ($Date->format('d-m'))
      {
      case '01-01':
        $Jour = 1;
        $Mois = 0; //Javascript compte les mois de 0 à 11.
        $TitreModale = $this->getParameter('TitreNouvelAn');
        $TexteModale = $this->getParameter('TexteNouvelAn');
        $TypeModale = 'NouvelAn';
        break;
      case '14-02':
        $Jour = 14;
        $Mois = 1; //Javascript compte les mois de 0 à 11.
        $TitreModale = $this->getParameter('TitreCupidon');
        $TexteModale = $this->getParameter('TexteCupidon');
        $TypeModale = 'Cupidon';
        break;
      case '01-04':
        $Jour = 1;
        $Mois = 3; //Javascript compte les mois de 0 à 11
        $TitreModale = $this->getParameter('TitrePoisson');
        $TexteModale = $this->getParameter('TextePoisson');
        $TypeModale = 'Poisson';
        break;
      case '25-12':
        $Jour = 25;
        $Mois = 11; //Javascript compte les mois de 0 à 11.
        $TitreModale = $this->getParameter('TitreCadeau');
        $TexteModale = $this->getParameter('TexteCadeau');
        $TypeModale = 'Cadeau';
        break;
      default:
        $Jour = 0;
        $Mois = 0; //Javascript compte les mois de 0 à 11.
        $TitreModale = '';
        $TexteModale = '';
        $TypeModale = '';
      }

    $Taille = strtolower($this->getParameter('Taille'));
    if (!in_array($Taille,['sm','md','lg','xl']))
      {
      $Taille = 'xl';
      }

    switch ($this->getParameter('Bordure'))
      {
      case 1:
        $Bordure = 'border border-success bordure-1';
        break;
      case 2:
        $Bordure = 'border border-success bordure-1 bordure-arrondie';
        break;
      case 3:
        $Bordure = 'border border-success bordure-1 rounded-circle';
        break;
      default:
        $Bordure = 'bordure-0';
      }

    return $this->render('accueil/calendrier/calendrier.html.twig',
        [
        'Indentation' => '    ',
        'Titre' => $this->getParameter('Titre'),
        'CouleurFond' => $this->getParameter('CouleurFond'),
        'CouleurTexte' => $this->getParameter('CouleurTexte'),
        'Noel' => $this->getParameter('Noel'),
        'Neige' => $this->getParameter('Neige'),
        'Forme' => $this->getParameter('Forme'),
        'Style' => $this->getParameter('Style'),
        'Bordure' => $Bordure,
        'Taille' => $Taille,
        'JourSpecial' => ['Jour'=>$Jour,'Mois'=>$Mois,'Titre'=>$TitreModale,'Texte'=>$TexteModale,'Type'=>$TypeModale],
        'MoisActivation' => $_ENV['Mois']
        ]
      );
    }
  }
