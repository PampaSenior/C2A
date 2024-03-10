<?php

namespace App\Service;

use App\Service\Application;

class Tirage
{
    private Application $application;
    /** @var array<string, string> $chemins */
    private array $chemins;

    public function __construct()
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
        }
    }

    /** @return array<int, array{Gagnant:string, Cadeau:string, Illustration?:string}> */
    public function getResultats(): array
    {
        $fichier = $this->chemins['Resultats'];

        if (!file_exists($fichier)) {
            $this->tirageAuSort();
        }

        return $this->chargementResultats();
    }

    private function tirageAuSort(): void
    {
        $contenu = $this->lectureCSV($this->chemins['Participants']);
        $participants = array_slice($contenu, 1, null, true); //Supprime uniquement la 1ere ligne d'entête

        $contenu = $this->lectureCSV($this->chemins['Lots']);
        $lots = array_slice($contenu, 1, 25, true); //Supprime la 1ere ligne d'entête et borne à 25 jours (= lignes)

        if ($participants != [] && $lots != []) {
            $nbElements = min(count($participants), count($lots));

            $gagnants = $participants;
            $cadeaux = $lots;
            $cas = 0;

            if (count($participants) > count($lots)) {
                $gagnants = array_rand($participants, $nbElements);
                $cas = 1;
            } elseif (count($participants) < count($lots)) {
                $cadeaux = array_rand($lots, $nbElements);
                $cas = 2;
            }

            $tableau = [];
            for ($i = 1; $i <= min($nbElements, 25); $i++) {
                switch ($cas) {
                    case 0:
                        $tableau[$i] = $gagnants[$i] . ',' . $cadeaux[$i];
                        break;
                    case 1:
                        $tableau[$i] = $participants[$gagnants[$i - 1]] . ',' . $cadeaux[$i];
                        break;
                    case 2:
                        $tableau[$i] = $gagnants[$i] . ',' . $lots[$cadeaux[$i - 1]];
                        break;
                }
            }

            shuffle($tableau);

            $resultats = 'Gagnant,Cadeau,Illustration';
            foreach ($tableau as $element) {
                $resultats = $resultats . "\n" . $element;
            }

            $this->ecritureCSV($this->chemins['Resultats'], $resultats);
        }
    }

    /** @return array<int, array{Gagnant:string, Cadeau:string, Illustration?:string}> */
    private function chargementResultats(): array
    {
        $resultats = [];

        $contenu = $this->lectureCSV($this->chemins['Resultats']);
        $contenu = array_slice($contenu, 1, 25, true); //Supprime la 1ere ligne d'entête et borne à 25 jours (= lignes)

        foreach ($contenu as $clef => $ligne) {
            $separation = explode(",", $ligne);

            if (count($separation) == 2 || count($separation) == 3) {
                $resultats[$clef] = [
                    'Gagnant' => $separation[0],
                    'Cadeau' => $separation[1],
                ];

                if (isset($separation[2]) && str_replace('"', '', $separation[2]) != "") {
                    $resultats[$clef]['Illustration'] = str_replace('"', '', $separation[2]);
                }
            }
        }

        return $resultats;
    }

    /** @return array<int, string> */
    private function lectureCSV(string $chemin): array
    {
        $resultat = [];

        if (file_exists($chemin)) {
            $resultat = file($chemin, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

        return $resultat === false ? [] : $resultat;
    }

    private function ecritureCSV(string $chemin, string $contenu): void
    {
        file_put_contents($chemin, $contenu, LOCK_EX);
    }
}
