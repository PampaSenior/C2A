<?php

namespace App\Service;

use App\Service\Ressource;
use App\Service\Parametre;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Verification
{
    private int $cas;

    public function __construct(ParameterBagInterface $parametre)
    {
        $this->cas = 0;
        $ressources = new Ressource($parametre); /* Note : ce service n'utilise pas les paramètres du .env */
        $parametres = new Parametre($parametre);

        if (
            !file_exists(
                $ressources->getFichier($ressources::FORMAT_CHEMIN, 'initialisation', $ressources::CAS_ORIGINAL)
            )
        ) {
            $this->cas = 1;
            return;
        }

        if ($parametres->getConfiguration() === []) { // S'il n'y a pas eu de chargement de la configuration
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
                return 'Verification.Erreur.Fichier';
            case 2:
                return 'Verification.Erreur.Variable';
            case 3:
                return 'Verification.Erreur.Tirage';
            default:
                return "";
        }
    }
}
