<?php

namespace App\Tests;

use App\Service\Ressource;
use App\Service\Tirage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TirageTest extends WebTestCase
{
    private array $fichiers;
    private Ressource $ressources;
    private Tirage $tirage;

    /**
     * Permet de vérifier la fonctionnalité de tirage au sort
     */
    public function testTirage(): void
    {
        $participants = "Participant\n" .
                        "1\n" .
                        "2\n" .
                        "3\n" .
                        "4\n" .
                        "5\n" .
                        "6\n" .
                        "7\n" .
                        "8\n" .
                        "9\n" .
                        "10\n" .
                        "11\n" .
                        "12\n" .
                        "13\n" .
                        "14\n" .
                        "15\n" .
                        "16\n" .
                        "17\n" .
                        "18\n" .
                        "19\n" .
                        "20\n" .
                        "21\n" .
                        "22\n" .
                        "23\n" .
                        "24\n" .
                        "25\n" .
                        "26\n" .
                        "27\n" .
                        "28\n" .
                        "29\n" .
                        "30";

        $lotsComplet = "Cadeau,Illustration\n" .
                "A,J1.png\n" .
                "B,J2.png\n" .
                "C,J3.png\n" .
                "D,J4.png\n" .
                "E,J5.png\n" .
                "F,J6.png\n" .
                "G,J7.png\n" .
                "H,J8.png\n" .
                "I,J9.png\n" .
                "J,J10.png\n" .
                "K,J11.png\n" .
                "L,J12.png\n" .
                "M,J13.png\n" .
                "N,J14.png\n" .
                "O,J15.png\n" .
                "P,J16.png\n" .
                "Q,J17.png\n" .
                "R,J18.png\n" .
                "S,J19.png\n" .
                "T,J20.png\n" .
                "U,J21.png\n" .
                "V,J22.png\n" .
                "W,J23.png\n" .
                "X,J24.png\n" .
                "Y,J25.png\n" .
                "Z,J26.png";

        $lotsPartiel = "Cadeau\n" .
                "A\n" .
                "B\n" .
                "C\n" .
                "D\n" .
                "E\n" .
                "F\n" .
                "G\n" .
                "H\n" .
                "I\n" .
                "J\n" .
                "K\n" .
                "L\n" .
                "M\n" .
                "N\n" .
                "O\n" .
                "P\n" .
                "Q\n" .
                "R\n" .
                "S\n" .
                "T\n" .
                "U\n" .
                "V\n" .
                "W\n" .
                "X\n" .
                "Y\n" .
                "Z";

        $this->fichiers = [
            'participants',
            'lots',
            'resultats',
        ];

        $this->majConfiguration();

        $this->originaux('sauvegarder');

        $this->ressources->ecriture('participants', $this->ressources::CAS_ORIGINAL, $participants);
        $this->ressources->ecriture('lots', $this->ressources::CAS_ORIGINAL, $lotsComplet);

        $_ENV['NOEL'] = '0'; // 24 jours
        $this->majConfiguration();
        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertFileExists(
            $this->ressources->getFichier(
                $this->ressources::FORMAT_CHEMIN,
                'resultats',
                $this->ressources::CAS_ORIGINAL
            )
        );
        $this->assertCount(24, $resultats);

        $this->nettoyage('resultats');

        $_ENV['NOEL'] = '1'; // 25 jours
        $this->majConfiguration();
        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertFileExists(
            $this->ressources->getFichier(
                $this->ressources::FORMAT_CHEMIN,
                'resultats',
                $this->ressources::CAS_ORIGINAL
            )
        );
        $this->assertCount(25, $resultats);

        $this->nettoyage('resultats');

        $_ENV['TIRAGE'] = '1'; // Participants aléatoires
        $this->majConfiguration();
        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertEquals($this->agregation($resultats), $this->bornerExtraction($lotsComplet, 25));

        $this->nettoyage('resultats');
        $this->ressources->ecriture('lots', $this->ressources::CAS_ORIGINAL, $lotsPartiel);

        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertEquals(array_column($resultats, 'Cadeau'), $this->bornerExtraction($lotsPartiel, 25));

        $this->nettoyage('resultats');
        $this->ressources->ecriture('lots', $this->ressources::CAS_ORIGINAL, $lotsComplet);

        $_ENV['TIRAGE'] = '2'; // Lots aléatoires
        $this->majConfiguration();
        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertEquals(array_column($resultats, 'Gagnant'), $this->bornerExtraction($participants, 25));

        $this->nettoyage('resultats');
        $this->nettoyage('participants');

        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertFileDoesNotExist(
            $this->ressources->getFichier(
                $this->ressources::FORMAT_CHEMIN,
                'resultats',
                $this->ressources::CAS_ORIGINAL
            )
        );
        $this->assertEquals([], $resultats);

        $this->ressources->ecriture('participants', $this->ressources::CAS_ORIGINAL, $participants);

        $this->nettoyage('lots');

        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertFileDoesNotExist(
            $this->ressources->getFichier(
                $this->ressources::FORMAT_CHEMIN,
                'resultats',
                $this->ressources::CAS_ORIGINAL
            )
        );
        $this->assertEquals([], $resultats);

        $this->ressources->ecriture('lots', $this->ressources::CAS_ORIGINAL, $lotsComplet);

        $this->nettoyages();

        $this->originaux('retablir');
    }

    private function agregation(array $tableau): array
    {
        return array_map(
            function ($tableau) {
                return $tableau['Cadeau'] . ',' . $tableau['Illustration'];
            },
            $tableau
        );
    }

    private function bornerExtraction(string $texte, int $nb): array
    {
        return array_slice(explode("\n", $texte), 1, $nb);
    }

    private function majConfiguration(): void
    {
        $client = static::createClient(); //Générer un navigateur fictif
        $parametre = $client->getContainer()->get(ParameterBagInterface::class); // Récupération d'un service

        $this->ressources = new Ressource($parametre);
        $this->tirage = new Tirage($parametre);

        self::ensureKernelShutdown();
    }

    private function nettoyages(): void
    {
        foreach ($this->fichiers as $fichier) {
            $this->nettoyage($fichier);
        };
    }

    private function nettoyage(string $fichier): void
    {
        $chemin = $this->ressources->getFichier(
            $this->ressources::FORMAT_CHEMIN,
            $fichier,
            $this->ressources::CAS_ORIGINAL
        );

        if (file_exists($chemin)) {
            unlink($chemin);
        }
    }

    private function originaux(string $sens): void
    {
        foreach ($this->fichiers as $fichier) {
            $this->original(
                $sens,
                $this->ressources->getFichier(
                    $this->ressources::FORMAT_CHEMIN,
                    $fichier,
                    $this->ressources::CAS_ORIGINAL
                )
            );
        }
    }

    private function original(string $sens, string $fichier): void
    {
        $suffixe = '.sauver';

        $cible = $fichier;
        $source = $cible . $suffixe;

        if (strtolower($sens) == 'sauvegarder') { //Permet d'être indépendant à la casse pour la clef
            $source = $fichier;
            $cible = $source . $suffixe;
        }

        if (file_exists($source)) {
            rename($source, $cible);
        }
    }
}
