<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
  {
  /**
   * @Route("/", name="Accueil", methods={"GET"})
   */
  public function Accueil(): Response
    {
    $Resultat = $this->VerificationConfiguration();
    if ($Resultat != '')
      {
      return $this->render('accueil/information/information.html.twig',
          [
          'Indentation' => '  ',
          'Message' => $Resultat
          ]
        );
      }

    $Date = new \Datetime('now');
    switch ($Date->format('d-m'))
      {
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
        'Taille' => $this->getParameter('Taille'),
        'JourSpecial' => ['Jour'=>$Jour,'Mois'=>$Mois,'Titre'=>$TitreModale,'Texte'=>$TexteModale,'Type'=>$TypeModale],
        'Jour' => $Date->format('d'),
        ]
      );
    }
  // Vérification de la configuration.
  private function VerificationConfiguration(): string
    {
    $Message = '';

    if (!file_exists('../.env.local'))
      {
      return 'Configuration.Information.Fichier';
      }

    try
      {
      $this->getParameter('Titre');
      $this->getParameter('TitreCupidon');
      $this->getParameter('TexteCupidon');
      $this->getParameter('TitrePoisson');
      $this->getParameter('TextePoisson');
      $this->getParameter('TitreCadeau');
      $this->getParameter('TexteCadeau');
      $this->getParameter('CouleurFond');
      $this->getParameter('CouleurTexte');
      $this->getParameter('Noel');
      $this->getParameter('Neige');
      $this->getParameter('Forme');
      $this->getParameter('Style');
      $this->getParameter('Taille');
      }
    catch (\Exception $Pb)
      {
      return 'Configuration.Information.Variable';
      }

    return $Message;
    }
  }
