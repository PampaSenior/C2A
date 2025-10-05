<?php

namespace App\Service;

use App\Entity\Application;
use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\Exception\EnvNotFoundException;

class Parametre
{
    use ClockAwareTrait;

    /** @var array<string, mixed> $configuration */
    private array $configuration;

    public function __construct(ParameterBagInterface $parametre)
    {
        $application = new Application();

        try {
            $this->configuration['Environnement'] = $parametre->get('kernel.environment');

            foreach (array_keys($application->getParametres()) as $clef) {
                $this->configuration[$clef] = $parametre->get($clef);
            }
        } catch (EnvNotFoundException) {
            $this->configuration = [];
        }
    }

    public function getMois(): int
    {
        /* Retourne le mois actuel en cas de développement sinon décembre */
        $nombre = $this->getTexte('Environnement') != "prod" ? $this->now()->format("n") : '12';
        return (int) $nombre;
    }

    public function getNb(): int
    {
        /* Retourne le nombre de jours à afficher dans le calendrier */
        return 24 + ($this->getBooleen('Noel') === true);
    }

    public function getAudio(): bool
    {
        /* Retourne l'activation ou non d'un son lors du clic sur le gagnant du jour */
        return ($this->getBooleen('Audio') === true);
    }

    /** @return array<string, string> */
    public function getNeige(): array
    {
        switch ($this->getEntier('Neige')) {
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
        switch ($this->getEntier('Forme')) {
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
        switch ($this->getEntier('Bordure')) {
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
        switch ($this->getEntier('Zoom')) {
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
        $taille = strtolower($this->getTexte('Taille'));
        if (!in_array($taille, ['sm', 'md', 'lg', 'xl'])) {
            $taille = 'xl';
        }

        return $taille;
    }

    /** @return array<string> */
    public function getTirage(): array
    {
        switch ($this->getEntier('Tirage')) {
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

        /* Pour être souple concernant l'écriture dans le .env.local */
        $pot2miel = array_change_key_case(
            $this->getTableau('Pot2Miel'),
            CASE_LOWER
        );

        foreach (array_keys($triche) as $clef) {
            if (array_key_exists($clef, $pot2miel)) {
                $triche[$clef] = $pot2miel[$clef];
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
                    'TitreModale' => $this->getTexte('TitreNouvelAn'),
                    'TexteModale' => $this->getTexte('TexteNouvelAn'),
                    'TypeModale' => 'NouvelAn',
                ];
            case '14-02':
                return [
                    'Jour' => 14,
                    'Mois' => 2,
                    'TitreModale' => $this->getTexte('TitreCupidon'),
                    'TexteModale' => $this->getTexte('TexteCupidon'),
                    'TypeModale' => 'Cupidon',
                ];
            case '01-04':
                return [
                    'Jour' => 1,
                    'Mois' => 4,
                    'TitreModale' => $this->getTexte('TitrePoisson'),
                    'TexteModale' => $this->getTexte('TextePoisson'),
                    'TypeModale' => 'Poisson',
                ];
            case '31-10':
                return [
                    'Jour' => 31,
                    'Mois' => 10,
                    'TitreModale' => $this->getTexte('TitreHorreur'),
                    'TexteModale' => $this->getTexte('TexteHorreur'),
                    'TypeModale' => 'Horreur',
                ];
            case '25-12':
                return [
                    'Jour' => 25,
                    'Mois' => 12,
                    'TitreModale' => $this->getTexte('TitreCadeau'),
                    'TexteModale' => $this->getTexte('TexteCadeau'),
                    'TypeModale' => 'Cadeau',
                ];
            default:
                return [];
        }
    }

    /** @return array<string, mixed> */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    private function getBooleen(string $clef): bool
    {
        $parametre = $this->configuration[$clef];

        settype($parametre, 'bool');

        return $parametre;
    }

    private function getEntier(string $clef): int
    {
        $parametre = $this->configuration[$clef];

        settype($parametre, 'int');

        return $parametre;
    }

    private function getTexte(string $clef): string
    {
        $parametre = $this->configuration[$clef];

        settype($parametre, 'string');

        return $parametre;
    }

    /** @return array<string, string> */
    private function getTableau(string $clef): array
    {
        $parametres = $this->configuration[$clef];

        settype($parametres, 'array');

        foreach ($parametres as $clef => $valeur) {
            settype($valeur, 'string');

            $parametres[$clef] = $valeur;
        }

        return $parametres;
    }
}
