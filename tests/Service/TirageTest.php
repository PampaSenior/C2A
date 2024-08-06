<?php

namespace App\Tests;

use App\Service\Ressource;
use App\Service\Tirage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Permet de vérifier la fonctionnalité de tirage au sort
 */
class TirageTest extends WebTestCase
{
    private array $fichiers;
    private Ressource $ressources;
    private Tirage $tirage;

    public function testTirage(): void
    {
        $this->fichiers = ['participants','lots','resultats'];

        $chemin = 'tests' . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'resultats.csv';

        $contenu = array_map('str_getcsv', file($chemin));
        $participants = implode("\n", array_column($contenu, 0));
        $lotsPartiel = implode("\n", array_column($contenu, 1));
        $lotsComplet = implode("\n", $this->assemblerTableau(array_column($contenu, 1), array_column($contenu, 2)));

        $this->majConfiguration();

        $this->originaux('sauvegarder');

        $this->ressources->ecriture('participants', $this->ressources::CAS_ORIGINAL, $participants);
        $this->ressources->ecriture('lots', $this->ressources::CAS_ORIGINAL, $lotsComplet);

        $tests = [
            'NOEL' => [
                [0, 24],
                [1, 25],
            ]
        ];

        foreach ($tests as $clef => $cas) {
            foreach ($cas as $info) {
                $_ENV[$clef] = $info[0];
                $this->majConfiguration();
                $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
                $this->assertFileExists(
                    $this->ressources->getFichier(
                        $this->ressources::FORMAT_CHEMIN,
                        'resultats',
                        $this->ressources::CAS_ORIGINAL
                    )
                );
                $this->assertCount($info[1], $resultats);

                $this->nettoyage('resultats');
            }
        }

        $_ENV['TIRAGE'] = '1'; // Participants aléatoires
        $this->majConfiguration();
        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertEquals(
            $this->assemblerTableau(array_column($resultats, 'cadeau'), array_column($resultats, 'illustration')),
            $this->bornerExtraction($lotsComplet, 25)
        );

        $this->nettoyage('resultats');
        $this->ressources->ecriture('lots', $this->ressources::CAS_ORIGINAL, $lotsPartiel);

        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertEquals(array_column($resultats, 'cadeau'), $this->bornerExtraction($lotsPartiel, 25));

        $this->nettoyage('resultats');
        $this->ressources->ecriture('lots', $this->ressources::CAS_ORIGINAL, $lotsComplet);

        $_ENV['TIRAGE'] = '2'; // Lots aléatoires
        $this->majConfiguration();
        $resultats = $this->tirage->getResultats(); // Génère le fichier de résultats
        $this->assertEquals(array_column($resultats, 'gagnant'), $this->bornerExtraction($participants, 25));

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

    private function assemblerTableau(array ...$tableaux): array
    {
        $resultat = [];

        $minimum = min(array_map('count', $tableaux));
        for ($i = 0; $i < $minimum; $i++) {
            $resultat[$i] = '';

            foreach ($tableaux as $tableau) {
                $resultat[$i] .= $tableau[$i] . ',';
            }

            $resultat[$i] = substr($resultat[$i], 0, -1);
        }

        return $resultat;
    }

    private function bornerExtraction(string $texte, int $nb): array
    {
        return array_slice(explode("\n", $texte), 1, $nb);
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

    private function majConfiguration(): void
    {
        $client = static::createClient(); //Générer un navigateur fictif
        $parametre = $client->getContainer()->get(ParameterBagInterface::class); // Récupération d'un service

        $this->ressources = new Ressource($parametre);
        $this->tirage = new Tirage($parametre);

        self::ensureKernelShutdown();
    }
}
