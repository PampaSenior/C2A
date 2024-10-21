<?php

namespace App\Service;

use App\Entity\Application;
use App\Service\Ressource;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Verification
{
    private int $cas;

    public function __construct(ParameterBagInterface $parametre)
    {
        $this->cas = 0;
        $application = new Application();
        $ressources = new Ressource($parametre); // Note : ce service n'utilise pas les paramètres du .env

        if (
            !file_exists(
                $ressources->getFichier($ressources::FORMAT_CHEMIN, 'initialisation', $ressources::CAS_ORIGINAL)
            )
        ) {
            $this->cas = 1;
            return;
        }

        try {
            foreach ($application->getParametres() as $clef) {
                $parametre->get($clef);
            }
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
