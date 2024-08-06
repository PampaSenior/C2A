<?php

namespace App\Controller;

use App\Service\Verification;
use App\Service\Parametre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Accueil extends AbstractController
{
    private Verification $verification;
    private Parametre $parametres;

    public function __construct(ParameterBagInterface $parametre)
    {
        $this->verification = new Verification($parametre);
        $this->parametres = new Parametre($parametre);
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
            'accueil/calendrier/calendrier.html.twig',
            [
                'Indentation' => '    ',
                'Titre' => $this->getParameter('Titre'),
                'CouleurFond' => $this->getParameter('CouleurFond'),
                'CouleurTexte' => $this->getParameter('CouleurTexte'),
                'Noel' => $this->parametres->getNb(),
                'Neige' => $this->parametres->getNeige(),
                'Forme' => $this->parametres->getForme(),
                'Style' => $this->getParameter('Style'),
                'Bordure' => $this->parametres->getBordure(),
                'Zoom' => $this->parametres->getZoom(),
                'Taille' => $this->parametres->getTaille(),
                'JourSpecial' => $this->parametres->getJourSpecial(),
                'MoisActivation' => $this->parametres->getMois()
            ]
        );
    }
}
