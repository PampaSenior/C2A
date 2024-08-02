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
        //Pour récupérer le mois côté serveur
        $date = $this->now(); //Date actuelle grâce au trait
        $mois = $date->format("n");

        //Retourne le mois actuel en cas de développement sinon décembre
        $nombre = $this->parametre->get('kernel.environment') != "prod" ? $mois : '12';
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
                $neige = [
                    'grande' => ' neige-flocon-grand ',
                    'moyenne' => ' neige-flocon-moyen ',
                    'petite' => ' neige-flocon-petit '
                ];
                break;
            case 2:
                $neige = [
                    'grande' => ' neige-boule-grande ',
                    'moyenne' => ' neige-boule-moyenne ',
                    'petite' => ' neige-boule-petite '
                ];
                break;
            default:
                $neige = [];
        }

        return $neige;
    }

    public function getForme(): string
    {
        switch ($this->parametre->get('Forme')) {
            case 1:
                $placement = 'losange';
                break;
            case 2:
                $placement = 'sapin';
                break;
            default:
                $placement = 'grille';
        }

        return $placement;
    }

    public function getBordure(): string
    {
        switch ($this->parametre->get('Bordure')) {
            case 1:
                $bordure = 'border border-success bordure-1';
                break;
            case 2:
                $bordure = 'border border-success bordure-1 bordure-arrondie';
                break;
            case 3:
                $bordure = 'border border-success bordure-1 rounded-circle';
                break;
            default:
                $bordure = 'bordure-0';
        }

        return $bordure;
    }

    public function getZoom(): string
    {
        switch ($this->parametre->get('Zoom')) {
            case 1:
                $zoom = 'position-absolute top-0 start-0';
                break;
            case 2:
                $zoom = 'position-absolute top-0 start-50';
                break;
            case 3:
                $zoom = 'position-absolute top-0 start-100';
                break;
            case 4:
                $zoom = 'position-absolute top-50 start-0';
                break;
            case 5:
                $zoom = 'position-absolute top-50 start-100';
                break;
            case 6:
                $zoom = 'position-absolute top-100 start-0';
                break;
            case 7:
                $zoom = 'position-absolute top-100 start-50';
                break;
            case 8:
                $zoom = 'position-absolute top-100 start-100';
                break;
            default:
                $zoom = '';
        }

        return $zoom;
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
                $aleatoire = ['participants'];
                break;
            case 2:
                $aleatoire = ['lots'];
                break;
            default:
                $aleatoire = ['participants', 'lots'];
        }

        return $aleatoire;
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
        $date = $this->now(); //Date actuelle grâce au trait
        switch ($date->format('d-m')) {
            case '01-01':
                $jourSpecial = [
                    'Jour' => 1,
                    'Mois' => 1,
                    'TitreModale' => $this->parametre->get('TitreNouvelAn'),
                    'TexteModale' => $this->parametre->get('TexteNouvelAn'),
                    'TypeModale' => 'NouvelAn',
                ];
                break;
            case '14-02':
                $jourSpecial = [
                    'Jour' => 14,
                    'Mois' => 2,
                    'TitreModale' => $this->parametre->get('TitreCupidon'),
                    'TexteModale' => $this->parametre->get('TexteCupidon'),
                    'TypeModale' => 'Cupidon',
                ];
                break;
            case '01-04':
                $jourSpecial = [
                    'Jour' => 1,
                    'Mois' => 4,
                    'TitreModale' => $this->parametre->get('TitrePoisson'),
                    'TexteModale' => $this->parametre->get('TextePoisson'),
                    'TypeModale' => 'Poisson',
                ];
                break;
            case '25-12':
                $jourSpecial = [
                    'Jour' => 25,
                    'Mois' => 12,
                    'TitreModale' => $this->parametre->get('TitreCadeau'),
                    'TexteModale' => $this->parametre->get('TexteCadeau'),
                    'TypeModale' => 'Cadeau',
                ];
                break;
            default:
                $jourSpecial = [];
        }

        return $jourSpecial;
    }
}
