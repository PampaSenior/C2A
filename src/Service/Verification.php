<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Verification
{
    private int $cas;

    public function __construct(ContainerBagInterface $parametre)
    {
        $this->cas = 0;

        if ($_ENV['Resultats'] == []) {
            $this->cas = 3;
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
            $parametre->get('Pot2Miel');
        } catch (\Exception $pb) {
            $this->cas = 2;
        }

        if (!file_exists('../.env.local')) {
            $this->cas = 1;
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
