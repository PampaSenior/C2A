<?php

namespace App\Controller;

use App\Service\Verification;
use App\Service\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Ajax')]
class Ajax extends AbstractController
{
    #[Route('/Cadeau/{Id}', name: 'Cadeau', requirements: ['Id' => '\d+'], methods: ['GET'])]
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

        $Noel = $this->getParameter('Noel') == 1; //TODO : Mettre cela dans Tirage mais après Verification...
        $Pot2Miel = $this->getParameter('Pot2Miel');

        //Pour récupérer le numéro du jour et le mois côté serveur
        $Date = new \DateTime('now');
        $Jour = $Date->format("j");
        $Mois = $Date->format("n");

        $Resultats = array_slice($_ENV['Resultats'], 0, 24 + $Noel, true); /*Récupère 24 éléments sauf si noël est activé TODO : Mettre cela dans Tirage mais après Verification...*/ //phpcs:ignore Generic.Files.LineLength

        $Gagnant = $Pot2Miel['Gagnant'];
        $Cadeau = $Pot2Miel['Cadeau'];
        $Illustration = '';

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

            $Gagnant = $Resultats[$Id]['Gagnant'];
            $Cadeau = $Resultats[$Id]['Cadeau'];
            $Illustration = $Resultats[$Id]['Illustration'];
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
