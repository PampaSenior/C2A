<?php

namespace App\Controller;

use App\Service\Verification;
use App\Service\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Ajax')]
class Ajax extends AbstractController
{
    #[Route('/Cadeau/{Id}', name: 'Cadeau', requirements: ['Id' => '\d+'], methods: ['GET'])]
    public function cadeau(int $Id, TranslatorInterface $Traducteur): JsonResponse
    {
        $Verification = new Verification($this->container->get('parameter_bag'));
        if (!$Verification->isValide()) {
            $Sortie = [
                'fr' => $Traducteur->trans($Verification->getErreur(), [], 'messages', 'fr'),
                'en' => $Traducteur->trans($Verification->getErreur(), [], 'messages', 'en')
            ];
            return new JsonResponse($Sortie);
        }

        $Noel = $this->getParameter('Noel') == 1; //TODO : Mettre cela dans Tirage mais après Verification...
        $Pot2Miel = $this->getParameter('Pot2Miel');

        //Pour récupérer le numéro du jour et le mois côté serveur
        $Date = new \DateTime('now');
        $Jour = $Date->format("j");
        $Mois = $Date->format("n");

        $Resultats = array_slice($_ENV['Resultats'], 0, 24 + $Noel, true); /*Récupère 24 éléments sauf si noël est activé TODO : Mettre cela dans Tirage mais après Verification...*/ //phpcs:ignore Generic.Files.LineLength
        if ($Id <= $Jour && $Mois == $_ENV['Mois'] && isset($Resultats[$Id])) {
            //Pour vérifier l'image d'illustration du cadeau
            if (isset($Resultats[$Id]['Illustration'])) {
                $Application = new Application();
                $Fichier = $Application->getDossierImage() . $Resultats[$Id]['Illustration'];
                $CheminOS = $Application->getDossierPublic() . $Fichier;
                $CheminURL = $this->generateUrl('Accueil') . $Fichier;

                $Resultats[$Id]['Illustration'] = "";
                if (file_exists($CheminOS)) {
                    $Resultats[$Id]['Illustration'] = $CheminURL;
                }
            }

            return new JsonResponse($Resultats[$Id]);
        }

        return new JsonResponse($Pot2Miel);
    }
}
