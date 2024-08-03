<?php

namespace App\Service;

use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Parametre
{
    use ClockAwareTrait;

    private ParameterBagInterface $parametre;

    public function __construct(ParameterBagInterface $parametre)
    {
        $this->parametre = $parametre;
    }

    public function getMois(): int
    {
        //Retourne le mois actuel en cas de développement sinon décembre
        $nombre = $this->parametre->get('kernel.environment') != "prod" ? $this->now()->format("n") : '12';
        return (int) $nombre;
    }

    public function getNb(): int
    {
        //Retourne le nombre de jours à afficher dans le calendrier
        return 24 + ($this->parametre->get('Noel') == 1);
    }

    /** @return array<string, string> */
    public function getNeige(): array
    {
        switch ($this->parametre->get('Neige')) {
            case 1:
                return [
                    'grande' => ' neige-flocon-grand ',
                    'moyenne' => ' neige-flocon-moyen ',
                    'petite' => ' neige-flocon-petit '
                ];
            case 2:
                return [
                    'grande' => ' neige-boule-grande ',
                    'moyenne' => ' neige-boule-moyenne ',
                    'petite' => ' neige-boule-petite '
                ];
            default:
                return [];
        }
    }

    public function getForme(): string
    {
        switch ($this->parametre->get('Forme')) {
            case 1:
                return 'losange';
            case 2:
                return 'sapin';
            default:
                return 'grille';
        }
    }

    public function getBordure(): string
    {
        switch ($this->parametre->get('Bordure')) {
            case 1:
                return 'border border-success bordure-1';
            case 2:
                return 'border border-success bordure-1 bordure-arrondie';
            case 3:
                return 'border border-success bordure-1 rounded-circle';
            default:
                return 'bordure-0';
        }
    }

    public function getZoom(): string
    {
        switch ($this->parametre->get('Zoom')) {
            case 1:
                return 'position-absolute top-0 start-0';
            case 2:
                return 'position-absolute top-0 start-50';
            case 3:
                return 'position-absolute top-0 start-100';
            case 4:
                return 'position-absolute top-50 start-0';
            case 5:
                return 'position-absolute top-50 start-100';
            case 6:
                return 'position-absolute top-100 start-0';
            case 7:
                return 'position-absolute top-100 start-50';
            case 8:
                return 'position-absolute top-100 start-100';
            default:
                return '';
        }
    }

    public function getTaille(): string
    {
        $taille = strtolower($this->parametre->get('Taille'));
        if (!in_array($taille, ['sm', 'md', 'lg', 'xl'])) {
            $taille = 'xl';
        }

        return $taille;
    }

    /** @return array<string> */
    public function getTirage(): array
    {
        switch ($this->parametre->get('Tirage')) {
            case 1:
                return ['participants'];
            case 2:
                return ['lots'];
            default:
                return ['participants', 'lots'];
        }
    }

    /** @return array{gagnant: string, cadeau: string, illustration: string} */
    public function getTriche(): array
    {
        $triche = [
            'gagnant' => '?',
            'cadeau' => '?',
            'illustration' => ''
        ];

        //Pour être souple concernant l'écriture dans le .env.local
        $configuration = array_change_key_case(
            $this->parametre->get('Pot2Miel'),
            CASE_LOWER
        );

        foreach (array_keys($triche) as $clef) {
            if (array_key_exists($clef, $configuration)) {
                $triche[$clef] = $configuration[$clef];
            }
        }

        return $triche;
    }

    /** @return array<string, int|string> */
    public function getJourSpecial(): array
    {
        switch ($this->now()->format('d-m')) {
            case '01-01':
                return [
                    'Jour' => 1,
                    'Mois' => 1,
                    'TitreModale' => $this->parametre->get('TitreNouvelAn'),
                    'TexteModale' => $this->parametre->get('TexteNouvelAn'),
                    'TypeModale' => 'NouvelAn',
                ];
            case '14-02':
                return [
                    'Jour' => 14,
                    'Mois' => 2,
                    'TitreModale' => $this->parametre->get('TitreCupidon'),
                    'TexteModale' => $this->parametre->get('TexteCupidon'),
                    'TypeModale' => 'Cupidon',
                ];
            case '01-04':
                return [
                    'Jour' => 1,
                    'Mois' => 4,
                    'TitreModale' => $this->parametre->get('TitrePoisson'),
                    'TexteModale' => $this->parametre->get('TextePoisson'),
                    'TypeModale' => 'Poisson',
                ];
            case '25-12':
                return [
                    'Jour' => 25,
                    'Mois' => 12,
                    'TitreModale' => $this->parametre->get('TitreCadeau'),
                    'TexteModale' => $this->parametre->get('TexteCadeau'),
                    'TypeModale' => 'Cadeau',
                ];
            default:
                return [];
        }
    }
}
