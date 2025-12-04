<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Administration')]
class Administration extends AbstractController
{
    #[Route(
        '',
        name: 'Administration',
        methods: [ 'GET' ]
    )]
    public function accueil(): Response
    {
        /** @var array<array{'icone': string, 'titre': string, 'description': string, 'lien': string}> $cadres */
        $cadres = [
            [
                'icone' => 'fa-tools text-danger',
                'titre' => 'Administration.Titre.Outils',
                'description' => 'Administration.Description.Outils',
                'lien' => $this->generateUrl('Configuration', [ 'cas' => 'Outil' ]),
            ],
            [
                'icone' => 'fa-palette text-warning',
                'titre' => 'Administration.Titre.Graphismes',
                'description' => 'Administration.Description.Graphismes',
                'lien' => $this->generateUrl('Configuration', [ 'cas' => 'Graphisme' ]),
            ],
            [
                'icone' => 'fa-egg text-tertiary',
                'titre' => 'Administration.Titre.Oeufs',
                'description' => 'Administration.Description.Oeufs',
                'lien' => $this->generateUrl('Configuration', [ 'cas' => 'Surprise' ]),
            ],
            [
                'icone' => 'fa-file-csv text-success',
                'titre' => 'Administration.Titre.Fichiers',
                'description' => 'Administration.Description.Fichiers',
                'lien' => $this->generateUrl('Configuration', [ 'cas' => 'Fichier' ]),
            ],
        ];

        return $this->render(
            'administration/accueil.html.twig',
            [
                'Indentation' => '  ',
                'Cadres' => $cadres,
            ]
        );
    }

    #[Route(
        '/Configuration/{cas}',
        name: 'Configuration',
        methods: [ 'GET', 'POST' ],
        requirements: [ 'cas' => '^(Outil|Graphisme|Surprise|Fichier)$' ]
    )]
    public function configuration(string $cas): Response
    {
        return $this->render(
            'configuration/accueil.html.twig',
            [
                'Indentation' => '  ',
                'Cas' => strtolower($cas),
            ]
        );
    }
}
