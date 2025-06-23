<?php

namespace App\Controller;

use App\Service\Verification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class Calendrier extends AbstractController
{
    private Verification $verification;

    public function __construct(ParameterBagInterface $parametre)
    {
        $this->verification = new Verification($parametre);
    }

    #[Route(
        '',
        name: 'Calendrier',
        methods: ['GET']
    )]
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
            'calendrier/accueil.html.twig',
            [
                'Indentation' => '  ',
            ]
        );
    }
}
