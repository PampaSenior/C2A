<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Fichier extends AbstractController
{
    #[Route(
        '/Fichier',
        name: 'Fichier',
        methods: ['GET']
    )]
    public function fichier(): Response
    {
        return $this->render(
            'fichier/telechargement.html.twig',
            [
                'Indentation' => '  ',
            ]
        );
    }
}
