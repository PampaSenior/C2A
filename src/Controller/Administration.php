<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Administration extends AbstractController
{
    #[Route(
        '/Administration',
        name: 'Administration',
        methods: ['GET']
    )]
    public function administration(): Response
    {
        /** @var array<array{'icone': string, 'titre': string, 'description': string, 'lien': string}> $cadres */
        $cadres = [
            [
                'icone' => 'fa-tools text-danger',
                'titre' => 'Administration.Titre.Outils',
                'description' => 'Administration.Description.Outils',
                'lien' => '#'
            ],
            [
                'icone' => 'fa-palette text-warning',
                'titre' => 'Administration.Titre.Graphismes',
                'description' => 'Administration.Description.Graphismes',
                'lien' => '#'
            ],
            [
                'icone' => 'fa-egg text-tertiary',
                'titre' => 'Administration.Titre.Oeufs',
                'description' => 'Administration.Description.Oeufs',
                'lien' => '#'
            ],
            [
                'icone' => 'fa-file-csv text-success',
                'titre' => 'Administration.Titre.Fichiers',
                'description' => 'Administration.Description.Fichiers',
                'lien' => '#'
            ],
        ];

        return $this->render(
            'administration/administration.html.twig',
            [
                'Indentation' => '  ',
                'Cadres' => $cadres,
            ]
        );
    }
}
