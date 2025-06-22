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
        /* Pour récupérer le mois côté serveur */
        $horloge = static::mockTime();
        $mois = (int) $horloge->now()->format("n"); /* Mois actuel */

        $_SERVER['APP_ENV'] = 'test'; /* Mois actuel pour autre chose que prod */
        $this->majConfiguration();
        $this->assertEquals($mois, $this->parametres->getMois());

        //$_SERVER['APP_ENV'] = 'prod'; // Mois de décembre (impossible à tester)
        //$this->majConfiguration();
        //$this->assertEquals(12, $this->parametres->getMois());
    }

    /** @SuppressWarnings(PHPMD.ExcessiveMethodLength) */
    public function testAffichages(): void
    {
        $tests = [
            'NOEL' => [
                'getNb' => [
                    ['false', 24],
                    ['true', 25],
                    ['off', 24],
                    ['on', 25],
                    ['no', 24],
                    ['yes', 25],
                    [0, 24],
                    [1, 25],
                ]
            ],
            'AUDIO' => [
                'getAudio' => [
                    ['false', false],
                    ['true', true],
                    ['off', false],
                    ['on', true],
                    ['no', false],
                    ['yes', true],
                    [0, false],
                    [1, true],
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
            'Horreur' => '2000-10-31',
            'Cadeau' => '2000-12-25'
        ];

        foreach ($dates as $clef => $date) {
            $this->majConfiguration();

            $horloge = static::mockTime($date);
            $this->parametres->setClock($horloge);
            $infos = $this->parametres->getJourSpecial();

            $this->assertEquals($clef, $infos['TypeModale']);
        }
    }

    public function testTricheSansIllustration(): void
    {
        $horloge = static::mockTime('-1 month');

        $client = static::createClient(); /* Générer un navigateur fictif */
        $client->request('GET', '/Ajax/JSON/Resultat/1'); /* On est sur un mois non autorisé*/

        $parametre = $client->getContainer()->get(ParameterBagInterface::class); /* Récupération d'un service */
        $this->parametres = new Parametre($parametre);
        $this->parametres->setClock($horloge);

        $pot2miel = $this->parametres->getTriche();
        $pot2miel['illustration'] = '';

        $this->assertEquals((array) json_decode($client->getResponse()->getContent()), $pot2miel);
    }

    public function testTricheAvecIllustration(): void
    {
        $horloge = static::mockTime('-1 month');

        $source = 'tests' . DIRECTORY_SEPARATOR . 'Annexe' . DIRECTORY_SEPARATOR . 'tricheur.png';
        $cible = 'public' . DIRECTORY_SEPARATOR . '3-images' . DIRECTORY_SEPARATOR . 'tricheur.png';
        copy($source, $cible);

        $client = static::createClient(); /* Générer un navigateur fictif */
        $client->request('GET', '/Ajax/JSON/Resultat/1'); /* On est sur un mois non autorisé*/

        $parametre = $client->getContainer()->get(ParameterBagInterface::class); /* Récupération d'un service */
        $this->parametres = new Parametre($parametre);
        $this->parametres->setClock($horloge);

        $pot2miel = $this->parametres->getTriche();
        $pot2miel['illustration'] = '3-images/' . $pot2miel['illustration'];

        $this->assertEquals((array) json_decode($client->getResponse()->getContent()), $pot2miel);

        unlink($cible);
    }

    private function majConfiguration(): void
    {
        $client = static::createClient(); /* Générer un navigateur fictif */
        $parametre = $client->getContainer()->get(ParameterBagInterface::class); /* Récupération d'un service */

        $this->parametres = new Parametre($parametre);

        self::ensureKernelShutdown();
    }
}
