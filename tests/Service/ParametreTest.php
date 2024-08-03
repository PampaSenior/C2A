<?php

namespace App\Tests;

use Symfony\Component\Clock\Test\ClockSensitiveTrait;
use App\Service\Parametre;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Permet de vérifier la fonctionnalité de transformation des paramètres complexes
 */
class ParametreTest extends WebTestCase
{
    use ClockSensitiveTrait;

    private Parametre $parametres;

    public function testEnvironnements(): void
    {
        //Pour récupérer le mois côté serveur
        $horloge = static::mockTime();
        $mois = (int) $horloge->now()->format("n"); // Mois actuel

        $_SERVER['APP_ENV'] = 'test'; // Mois actuel pour autre chose que prod
        $this->majConfiguration();
        $this->assertEquals($mois, $this->parametres->getMois());

        //$_SERVER['APP_ENV'] = 'prod'; // Mois de décembre (impossible à tester)
        //$this->majConfiguration();
        //$this->assertEquals(12, $this->parametres->getMois());
    }

    public function testAffichages(): void
    {
        $tests = [
            'NOEL' => [
                'getNb' => [
                    [0, 24],
                    [1, 25],
                    [2, 24],
                ]
            ],
            'NEIGE' => [
                'getNeige' => [
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
                ]
            ],
            'FORME' => [
                'getForme' => [
                    [0, 'grille'],
                    [1, 'losange'],
                    [2, 'sapin'],
                    [3, 'grille'],
                ]
            ],
            'BORDURE' => [
                'getBordure' => [
                    [0, 'bordure-0'],
                    [1, 'border border-success bordure-1'],
                    [2, 'border border-success bordure-1 bordure-arrondie'],
                    [3, 'border border-success bordure-1 rounded-circle'],
                    [4, 'bordure-0'],
                ]
            ],
            'ZOOM' => [
                'getZoom' => [
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
                ]
            ],
            'TAILLE' => [
                'getTaille' => [
                    ['sm', 'sm'],
                    ['md', 'md'],
                    ['lg', 'lg'],
                    ['xl', 'xl'],
                    ['lol', 'xl'],
                ]
            ],
            'TIRAGE' => [
                'getTirage' => [
                    [0, ['participants', 'lots']],
                    [1, ['participants']],
                    [2, ['lots']],
                    [3, ['participants', 'lots']],
                ]
            ],
        ];

        foreach ($tests as $parametre => $cas) {
            foreach ($cas as $fonction => $infos) {
                foreach ($infos as $info) {
                    $_ENV[$parametre] = $info[0];
                    $this->majConfiguration();
                    $this->assertEquals($info[1], $this->parametres->{$fonction}());
                }
            }
        }
    }

    public function testJoursSpeciaux(): void
    {
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
