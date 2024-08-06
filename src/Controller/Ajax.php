<?php

namespace App\Controller;

use App\Service\Verification;
use App\Service\Ressource;
use App\Service\Parametre;
use App\Service\Tirage;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Ajax')]
class Ajax extends AbstractController
{
    private Verification $verification;
    private Ressource $ressources;
    private Parametre $parametres;
    private Tirage $tirage;
    private TranslatorInterface $traducteur;

    public function __construct(ParameterBagInterface $parametre, TranslatorInterface $traducteur)
    {
        $this->verification = new Verification($parametre);
        $this->ressources = new Ressource($parametre);
        $this->parametres = new Parametre($parametre);
        $this->tirage = new Tirage($parametre);

        $this->traducteur = $traducteur;
    }

    #[Route(
        '/HTML/Resultat/{id}',
        name: 'ResultatHTML',
        requirements: ['id' => '^([1-9]|1[0-9]|2[0-5])$'],
        methods: ['GET']
    )]
    public function resultatHTML(int $id): Response
    {
        if (!$this->verification->isValide()) {
            return $this->render(
                'message/information.html.twig',
                [
                    'Indentation' => '  ',
                    'Message' => $this->verification->getErreur()
                ]
            );
        }

        return $this->render(
            'accueil/calendrier/_jour.html.twig',
            [
                'Indentation' => '    ',
                'Jour' => $id,
                'Couleur' => $this->getParameter('CouleurTexte'),
                'Bordure' => $this->parametres->getBordure(),
                'Zoom' => $this->parametres->getZoom(),
                'Taille' => $this->parametres->getTaille(),
                'Resultat' => $this->getResultat($id),
            ]
        );
    }

    #[Route(
        '/JSON/Resultat/{id}',
        name: 'ResultatJSON',
        requirements: ['id' => '^([1-9]|1[0-9]|2[0-5])$'],
        methods: ['GET']
    )]
    public function resultatJSON(int $id): JsonResponse
    {
        if (!$this->verification->isValide()) {
            return new JsonResponse(
                [
                'fr' => $this->traducteur->trans($this->verification->getErreur(), [], 'messages', 'fr'),
                'en' => $this->traducteur->trans($this->verification->getErreur(), [], 'messages', 'en')
                ]
            );
        }

        return new JsonResponse($this->getResultat($id));
    }

    /** @return array{gagnant: string, cadeau: string, illustration: string} */
    private function getResultat(int $id): array
    {
        //Pour récupérer le numéro du jour et le mois côté serveur
        $date = new DateTime('now');
        $jour = (int) $date->format("j");
        $mois = (int) $date->format("n");

        if ($id <= $jour && $mois == $this->parametres->getMois()) {
            $resultats = $this->tirage->getResultats();

            $resultat = [
                'gagnant' => $resultats[$id - 1]['gagnant'],
                'cadeau' => $resultats[$id - 1]['cadeau'],
                'illustration' => '',
            ];

            //Pour vérifier l'image d'illustration du cadeau
            if (isset($resultats[$id - 1]['illustration'])) {
                $cheminURL = $this->ressources->getDossier(
                    $this->ressources::FORMAT_URL,
                    'images'
                ) . $resultats[$id - 1]['illustration'];

                $cheminOS = $this->ressources->getDossier(
                    $this->ressources::FORMAT_CHEMIN,
                    'images'
                ) . $resultats[$id - 1]['illustration'];

                if (file_exists($cheminOS)) {
                    $resultat['illustration'] = $cheminURL;
                }
            }

            return $resultat;
        }

        return $this->parametres->getTriche();
    }
}
