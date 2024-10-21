<?php

namespace App\Controller;

use App\Service\Verification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Accueil extends AbstractController
{
    private Verification $verification;

    public function __construct(ParameterBagInterface $parametre)
    {
        $this->verification = new Verification($parametre);
    }

    #[Route('/', name: 'Accueil', methods: ['GET'])]
    public function accueil(): Response
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
            'accueil/calendrier.html.twig',
            [
                'Indentation' => '  ',
                'Titre' => $this->getParameter('Titre'),
                'CouleurFond' => $this->getParameter('CouleurFond'),
                'CouleurTexte' => $this->getParameter('CouleurTexte'),
                'Style' => $this->getParameter('Style'),
            ]
        );
    }
}
