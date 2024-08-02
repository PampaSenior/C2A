<?php

namespace App\Controller;

use App\Service\Verification;
use App\Service\Parametre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Accueil extends AbstractController
{
    #[Route('/', name: 'Accueil', methods: ['GET'])]
    public function accueil(): Response
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

        return $this->render(
            'accueil/calendrier/calendrier.html.twig',
            [
                'Indentation' => '    ',
                'Titre' => $this->getParameter('Titre'),
                'CouleurFond' => $this->getParameter('CouleurFond'),
                'CouleurTexte' => $this->getParameter('CouleurTexte'),
                'Noel' => $parametres->getNb(),
                'Neige' => $parametres->getNeige(),
                'Forme' => $parametres->getForme(),
                'Style' => $this->getParameter('Style'),
                'Bordure' => $parametres->getBordure(),
                'Zoom' => $parametres->getZoom(),
                'Taille' => $parametres->getTaille(),
                'JourSpecial' => $parametres->getJourSpecial(),
                'MoisActivation' => $parametres->getMois()
            ]
        );
    }
}
