<?php

namespace App\Tests;

use App\Service\Application;
use App\Service\Tirage;
use PHPUnit\Framework\TestCase;

class TirageTest extends TestCase
{
    private Application $application;
    /** @var array<string, string> $Chemins */
    private array $chemins;
    /** @var array<string, string> $CSV */
    private array $csv;

    /**
     * Permet de vérifier la fonctionnalité de tirage au sort
     */
    public function testTirage(): void
    {
        $this->application = new Application();

        $dossier = $this->application->getDossierPublic() . $this->application->getDossierDocument();

        $fichiers = [
            'Resultats' => 'resultats.csv',
            'Participants' => 'participants.csv',
            'Lots' => 'lots.csv'
        ];

        foreach ($fichiers as $clef => $fichier) {
            $this->chemins[$clef] = $dossier . $fichier;
            $this->csv[$clef] = '';
        }

        //Cas où "participants" possède moins d'éléments que "lots"
        $this->generationDesCas(
            "Participant\n1\n2",
            "Cadeau,Illustration\nA,J1.png\nB\nC,J3.png\nD,"
        );

        //Cas où "participants" possède autant d'éléments que "lots"
        $this->generationDesCas(
            "Participant\n1\n2\n3\n4",
            "Cadeau,Illustration\nA,J1.png\nB\nC,J3.png\nD,"
        );

        //Cas où "participants" possède plus d'éléments que "lots"
        $this->generationDesCas(
            "Participant\n1\n2\n2\n4\n5",
            "Cadeau,Illustration\nA,J1.png\nB\nC,J3.png\nD,"
        );
    }

    private function generationDesCas(string $donneesParticipants, string $donneesLots): void
    {
        //Stockage des données des fichiers qui pourraient déjà être présents
        $this->traitementCSV('Lecture');

        //Suppression des fichiers qui pourraient déjà être présents
        $this->traitementCSV('Suppression');

        //Mettre les données de tests dans les fichiers
        file_put_contents($this->chemins['Participants'], $donneesParticipants, LOCK_EX);
        file_put_contents($this->chemins['Lots'], $donneesLots, LOCK_EX);

        //Réaliser la génération des résultats
        $tirage = new Tirage();
        $tirage->getResultats();

        //Tester la génération du fichier
        $this->assertFileExists($this->chemins['Resultats']);

        //Suppression des fichiers de test générés
        $this->traitementCSV('Suppression');

        //Remettre les données d'origine dans les fichiers
        $this->traitementCSV('Ecriture');
    }

    private function traitementCSV(string $choix): void
    {
        foreach ($this->chemins as $clef => $chemin) {
            switch ($choix) {
                case 'Lecture':
                    $contenu = file($chemin, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $this->csv[$clef] = implode("\n", $contenu === false ? null : $contenu);
                    break;
                case 'Suppression':
                    unlink($chemin);
                    break;
                case 'Ecriture':
                    file_put_contents($chemin, $this->csv[$clef], LOCK_EX);
                    break;
            }
        }
    }
}
