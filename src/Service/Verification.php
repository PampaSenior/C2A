<?php

namespace App\Service;

use App\Service\Ressource;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Verification
{
    private int $cas;

    public function __construct(ParameterBagInterface $parametre)
    {
        $this->cas = 0;

        // Note : ce service n'utilise pas les paramètres du .env
        $ressources = new Ressource($parametre);

        if (
            !file_exists(
                $ressources->getFichier($ressources::FORMAT_CHEMIN, 'initialisation', $ressources::CAS_ORIGINAL)
            )
        ) {
            $this->cas = 1;
            return;
        }

        try {
            $parametre->get('Titre');
            $parametre->get('TitreNouvelAn');
            $parametre->get('TexteNouvelAn');
            $parametre->get('TitreCupidon');
            $parametre->get('TexteCupidon');
            $parametre->get('TitrePoisson');
            $parametre->get('TextePoisson');
            $parametre->get('TitreCadeau');
            $parametre->get('TexteCadeau');
            $parametre->get('CouleurFond');
            $parametre->get('CouleurTexte');
            $parametre->get('Noel');
            $parametre->get('Neige');
            $parametre->get('Forme');
            $parametre->get('Style');
            $parametre->get('Bordure');
            $parametre->get('Zoom');
            $parametre->get('Taille');
            $parametre->get('Tirage');
            $parametre->get('Pot2Miel');
        } catch (\Exception $pb) {
            $this->cas = 2;
            return;
        }

        if (
            !file_exists(
                $ressources->getFichier($ressources::FORMAT_CHEMIN, 'resultats', $ressources::CAS_ORIGINAL)
            ) &&
            (
                !file_exists(
                    $ressources->getFichier($ressources::FORMAT_CHEMIN, 'participants', $ressources::CAS_ORIGINAL)
                ) ||
                !file_exists(
                    $ressources->getFichier($ressources::FORMAT_CHEMIN, 'lots', $ressources::CAS_ORIGINAL)
                )
            )
        ) {
            $this->cas = 3;
            return;
        }
    }

    public function isValide(): bool
    {
        return $this->cas === 0 ? true : false ;
    }

    public function getErreur(): string
    {
        switch ($this->cas) {
            case 1:
                return 'Information.Configuration.Fichier';
            case 2:
                return 'Information.Configuration.Variable';
            case 3:
                return 'Information.Configuration.Tirage';
            default:
                return "";
        }
    }
}
