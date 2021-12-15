<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

  /**
   * @Route("/Ajax")
   */
class AjaxController extends AbstractController
  {
  /**
   * @Route("/Cadeau/{Id}", name="Cadeau", requirements={"Id"="\d+"}, methods={"GET"})
   */
  public function Cadeau(int $Id): JsonResponse
    {
    $Pot2Miel = $this->getParameter('Pot2Miel');
    $Resultats = $this->getParameter('Resultats');

    //Pour contraindre Id de 1 à nb de résultats
    $Id = min($Id,count($Resultats));
    $Id = max(1,$Id);

    //Pour récupérer le numéro du jour et le mois côté serveur
    $Temps = $this->date = new \DateTime('now');
    $Jour = $Temps->format("j");
    $Mois = $Temps->format("m");

    if ($Id <= $Jour && $Mois == 12)
      {
      return new JsonResponse($Resultats[$Id]);
      }
    else
      {
      return new JsonResponse($Pot2Miel);
      }
    }
  }
