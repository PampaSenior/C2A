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
    $Date = new \Datetime('now');
    if ($Date->format('d-m') == '25-12')
      {
      $J25 = true;
      }
    else
      {
      $J25 = false;
      }

    return $this->render('accueil/accueil.html.twig',
        [
        'Indentation' => '    ',
        'Titre' => $this->getParameter('Titre'),
        'TitreModale' => $this->getParameter('TitreModale'),
        'TexteModale' => $this->getParameter('TexteModale'),
        'CouleurFond' => $this->getParameter('CouleurFond'),
        'CouleurTexte' => $this->getParameter('CouleurTexte'),
        'Noel' => $this->getParameter('Noel'),
        'Forme' => $this->getParameter('Forme'),
        'Style' => $this->getParameter('Style'),
        'Taille' => $this->getParameter('Taille'),
        'J25' => $J25,
        ]
      );
    }
  }
