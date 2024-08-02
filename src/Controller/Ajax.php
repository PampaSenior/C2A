<?php

namespace App\Controller;

use App\Service\Verification;
use App\Service\Tirage;
use App\Service\Ressource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Ajax')]
class Ajax extends AbstractController
{
    #[Route('/Cadeau/{Id}', name: 'Cadeau', requirements: ['Id' => '^([1-9]|1[0-9]|2[0-5])$'], methods: ['GET'])]
    public function cadeau(int $Id): Response
    {
        $Verification = new Verification($this->container->get('parameter_bag'));
        if (!$Verification->isValide()) {
            return $this->render(
                'message/information.html.twig',
                [
                    'Indentation' => '  ',
                    'Message' => $Verification->getErreur()
                ]
            );
        }

        switch ($this->getParameter('Bordure')) {
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

        switch ($this->getParameter('Zoom')) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
                $Zoom = $this->getParameter('Zoom');
                break;
            default:
                $Zoom = 0;
        }

        $Pot2Miel = $this->getParameter('Pot2Miel');

        $Tirage = new Tirage($this->container->get('parameter_bag'));
        $Resultats = $Tirage->getResultats();

        //Pour récupérer le numéro du jour et le mois côté serveur
        $Date = new \DateTime('now');
        $Jour = $Date->format("j");
        $Mois = $Date->format("n");

        $Gagnant = $Pot2Miel['Gagnant'];
        $Cadeau = $Pot2Miel['Cadeau'];
        $Illustration = '';

        $moisActivation = $this->getParameter('kernel.environment') != "prod" ? $Date->format('n') : 12; /*Met le mois actuel en cas de développement*/ /*phpcs:ignore Generic.Files.LineLength*/

        if ($Id <= $Jour && $Mois == $moisActivation && isset($Resultats[$Id - 1])) {
            //Pour vérifier l'image d'illustration du cadeau
            if (isset($Resultats[$Id - 1]['Illustration'])) {
                $ressources = new Ressource($this->container->get('parameter_bag'));
                $cheminOS = $ressources->getDossier(
                    $ressources::FORMAT_CHEMIN,
                    'images'
                ) . $Resultats[$Id - 1]['Illustration'];
                $cheminURL = $ressources->getDossier(
                    $ressources::FORMAT_URL,
                    'images'
                ) . $Resultats[$Id - 1]['Illustration'];

                $Resultats[$Id - 1]['Illustration'] = "";
                if (file_exists($cheminOS)) {
                    $Resultats[$Id - 1]['Illustration'] = $cheminURL;
                }
            } else {
                $Resultats[$Id - 1]['Illustration'] = "";
            }

            $Gagnant = $Resultats[$Id - 1]['Gagnant'];
            $Cadeau = $Resultats[$Id - 1]['Cadeau'];
            $Illustration = $Resultats[$Id - 1]['Illustration'];
        }

        return $this->render(
            'accueil/calendrier/_jour.html.twig',
            [
                'Indentation' => '    ',
                'Jour' => $Id,
                'Couleur' => $this->getParameter('CouleurTexte'),
                'Bordure' => $Bordure,
                'Zoom' => $Zoom,
                'Taille' => $this->getParameter('Taille'),
                'Gagnant' => $Gagnant,
                'Cadeau' => $Cadeau,
                'Illustration' => $Illustration
            ]
        );
    }
}
