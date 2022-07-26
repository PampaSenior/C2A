<?php

namespace App\Controller;

use App\Service\Verification;
use App\Service\Application;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Contracts\Translation\TranslatorInterface;

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
  public function Cadeau(int $Id, TranslatorInterface $Translator): JsonResponse
    {
    $Verification = new Verification($this->container->get('parameter_bag'));
    if (!$Verification->isValide())
      {
      $Sortie = [
                'fr'=>$Translator->trans($Verification->getErreur(),[],'messages','fr'),
                'en'=>$Translator->trans($Verification->getErreur(),[],'messages','en')
                ];
      return new JsonResponse($Sortie);
      }

    $Pot2Miel = $this->getParameter('Pot2Miel');
    $Resultats = $this->getParameter('Resultats');

    //Pour récupérer le numéro du jour et le mois côté serveur
    $Date = new \DateTime('now');
    $Jour = $Date->format("j");
    $Mois = $Date->format("n");

    //Pour mettre le mois actuel en cas de développement
    $MoisActivation = $this->getParameter('kernel.environment') == "dev" ? $Mois : 12;

    if ($Id <= $Jour && $Mois == $MoisActivation && isset($Resultats[$Id]))
      {
      //Pour vérifier l'image d'illustration du cadeau
      if (isset($Resultats[$Id]['Illustration']))
        {
        $Dossier = (new Application())->getDossierImage();
        $Fichier = $Dossier.$Resultats[$Id]['Illustration'];
        $CheminOS = '../public/'.$Fichier;
        $CheminURL = $this->generateUrl('Accueil').$Fichier;

        if (file_exists($CheminOS))
          {
          $Resultats[$Id]['Illustration']=$CheminURL;
          }
        else
          {
          $Resultats[$Id]['Illustration']="";
          }
        }

      return new JsonResponse($Resultats[$Id]);
      }
    else
      {
      return new JsonResponse($Pot2Miel);
      }
    }
  }
