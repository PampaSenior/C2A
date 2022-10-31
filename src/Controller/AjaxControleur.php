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
class AjaxControleur extends AbstractController
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

    $Noel = $this->getParameter('Noel') == 1; //TODO : Mettre cela dans Tirage mais après Verification...
    $Pot2Miel = $this->getParameter('Pot2Miel');

    //Pour récupérer le numéro du jour et le mois côté serveur
    $Date = new \DateTime('now');
    $Jour = $Date->format("j");
    $Mois = $Date->format("n");

    $Resultats = array_slice($_ENV['Resultats'],0,24+$Noel,true); //On ne prends que 24 éléments sauf si noël est activé; TODO : Mettre cela dans Tirage mais après Verification...
    if ($Id <= $Jour && $Mois == $_ENV['Mois'] && isset($Resultats[$Id]))
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
