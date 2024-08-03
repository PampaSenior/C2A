<?php

namespace App\Controller;

use App\Service\Verification;
use App\Service\Parametre;
use App\Service\Tirage;
use App\Service\Ressource;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Ajax')]
class Ajax extends AbstractController
{
    #[Route('/Resultat/{id}', name: 'Resultat', requirements: ['id' => '^([1-9]|1[0-9]|2[0-5])$'], methods: ['GET'])]
    public function resultat(int $id): Response
    {
        $verification = new Verification($this->container->get('parameter_bag'));
        if (!$verification->isValide()) {
            return $this->render(
                'message/information.html.twig',
                [
                    'Indentation' => '  ',
                    'Message' => $verification->getErreur()
                ]
            );
        }

        $parametres = new Parametre($this->container->get('parameter_bag'));
        $resultat = $parametres->getTriche();

        $tirage = new Tirage($this->container->get('parameter_bag'));
        $resultats = $tirage->getResultats();

        //Pour récupérer le numéro du jour et le mois côté serveur
        $date = new DateTime('now');
        $jour = (int) $date->format("j");
        $mois = (int) $date->format("n");

        if ($id <= $jour && $mois == $parametres->getMois() && isset($resultats[$id - 1])) {
            $resultat['gagnant'] = $resultats[$id - 1]['gagnant'];
            $resultat['cadeau'] = $resultats[$id - 1]['cadeau'];

            //Pour vérifier l'image d'illustration du cadeau
            if (isset($resultats[$id - 1]['illustration'])) {
                $ressources = new Ressource($this->container->get('parameter_bag'));

                $cheminOS = $ressources->getDossier(
                    $ressources::FORMAT_CHEMIN,
                    'images'
                ) . $resultats[$id - 1]['illustration'];

                $cheminURL = $ressources->getDossier(
                    $ressources::FORMAT_URL,
                    'images'
                ) . $resultats[$id - 1]['illustration'];

                if (file_exists($cheminOS)) {
                    $resultat['illustration'] = $cheminURL;
                }
            }
        }

        return $this->render(
            'accueil/calendrier/_jour.html.twig',
            [
                'Indentation' => '    ',
                'Jour' => $id,
                'Couleur' => $this->getParameter('CouleurTexte'),
                'Bordure' => $parametres->getBordure(),
                'Zoom' => $parametres->getZoom(),
                'Taille' => $parametres->getTaille(),
                'Resultat' => $resultat,
            ]
        );
    }
}
