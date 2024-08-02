<?php

namespace App\Tests;

use Symfony\Component\Clock\Test\ClockSensitiveTrait;
use App\Service\Parametre;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParametreTest extends WebTestCase
{
    use ClockSensitiveTrait;

    private Parametre $parametres;

    /**
     * Permet de vérifier la fonctionnalité de transformation des paramètres complexes
     */
    public function testParametre(): void
    {
        //Pour récupérer le mois côté serveur
        $date = new \DateTime('now');
        $mois = (int) $date->format("n"); // Mois actuel
        $_SERVER['APP_ENV'] = 'test'; // Mois actuel pour autre chose que prod
        $this->majConfiguration();
        $this->assertEquals($mois, $this->parametres->getMois());

        //$_SERVER['APP_ENV'] = 'prod'; // Mois de décembre (impossible à tester)
        //$this->majConfiguration();
        //$this->assertEquals(12, $this->parametres->getMois());

        //Appariement parametre -> fonction pour les paramètres de $_ENV
        $fonctions = [
            'NOEL' => 'getNb',
            'NEIGE' => 'getNeige',
            'FORME' => 'getForme',
            'BORDURE' => 'getBordure',
            'ZOOM' => 'getZoom',
            'TAILLE' => 'getTaille',
            'TIRAGE' => 'getTirage',
        ];

        //Appariement parametre -> tests pour les paramètres de $_ENV
        $tests = [
            'NOEL' => [
                [0, 24],
                [1, 25],
                [2, 24],
            ],
            'NEIGE' => [
                [0, []],
                [
                    1,
                    [
                        'grande' => ' neige-flocon-grand ',
                        'moyenne' => ' neige-flocon-moyen ',
                        'petite' => ' neige-flocon-petit ',
                    ]
                ],
                [
                    2,
                    [
                        'grande' => ' neige-boule-grande ',
                        'moyenne' => ' neige-boule-moyenne ',
                        'petite' => ' neige-boule-petite ',
                    ]
                ],
                [3, []],
            ],
            'FORME' => [
                [0, 'grille'],
                [1, 'losange'],
                [2, 'sapin'],
                [3, 'grille'],
            ],
            'BORDURE' => [
                [0, 'bordure-0'],
                [1, 'border border-success bordure-1'],
                [2, 'border border-success bordure-1 bordure-arrondie'],
                [3, 'border border-success bordure-1 rounded-circle'],
                [4, 'bordure-0'],
            ],
            'ZOOM' => [
                [0, ''],
                [1, 'position-absolute top-0 start-0'],
                [2, 'position-absolute top-0 start-50'],
                [3, 'position-absolute top-0 start-100'],
                [4, 'position-absolute top-50 start-0'],
                [5, 'position-absolute top-50 start-100'],
                [6, 'position-absolute top-100 start-0'],
                [7, 'position-absolute top-100 start-50'],
                [8, 'position-absolute top-100 start-100'],
                [9, ''],
            ],
            'TAILLE' => [
                ['sm', 'sm'],
                ['md', 'md'],
                ['lg', 'lg'],
                ['xl', 'xl'],
                ['lol', 'xl'],
            ],
            'TIRAGE' => [
                [0, ['participants', 'lots']],
                [1, ['participants']],
                [2, ['lots']],
                [3, ['participants', 'lots']],
            ],
        ];

        foreach ($tests as $parametre => $infos) {
            foreach ($infos as $info) {
                $_ENV[$parametre] = $info[0];
                $this->majConfiguration();
                $this->assertEquals($info[1], $this->parametres->{$fonctions[$parametre]}());
            }
        }

        $dates = [
            'NouvelAn' => '2000-01-01',
            'Cupidon' => '2000-02-14',
            'Poisson' => '2000-04-01',
            'Cadeau' => '2000-12-25'
        ];

        foreach ($dates as $clef => $date) {
            $horloge = static::mockTime($date);

            $this->majConfiguration();

            $this->parametres->setClock($horloge);
            $infos = $this->parametres->getJourSpecial();

            $this->assertEquals($clef, $infos['TypeModale']);
        }
    }

    private function majConfiguration(): void
    {
        $client = static::createClient(); //Générer un navigateur fictif
        $parametre = $client->getContainer()->get(ParameterBagInterface::class); // Récupération d'un service

        $this->parametres = new Parametre($parametre);

        self::ensureKernelShutdown();
    }
}
