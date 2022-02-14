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
    if ($Date->format('d-m') == '25-12')
      {
      $J25 = true;
      }
    else
      {
      $J25 = false;
      }

    return $this->render('accueil/calendrier/calendrier.html.twig',
        [
        'Indentation' => '    ',
        'Titre' => $this->getParameter('Titre'),
        'TitreModale' => $this->getParameter('TitreModale'),
        'TexteModale' => $this->getParameter('TexteModale'),
        'CouleurFond' => $this->getParameter('CouleurFond'),
        'CouleurTexte' => $this->getParameter('CouleurTexte'),
        'Noel' => $this->getParameter('Noel'),
        'Neige' => $this->getParameter('Neige'),
        'Forme' => $this->getParameter('Forme'),
        'Style' => $this->getParameter('Style'),
        'Taille' => $this->getParameter('Taille'),
        'J25' => $J25,
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
      $this->getParameter('TitreModale');
      $this->getParameter('TexteModale');
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
