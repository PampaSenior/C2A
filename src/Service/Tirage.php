<?php

namespace App\Service;

use App\Service\Ressource;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Tirage
{
    /** @var array<string> $modes */
    private array $modes;
    private int $nb;
    private Ressource $ressources;

    public function __construct(ParameterBagInterface $parametre)
    {
        switch ($parametre->get('Tirage')) {
            case 1:
                $this->modes = ['participants'];
                break;
            case 2:
                $this->modes = ['lots'];
                break;
            default:
                $this->modes = ['participants', 'lots'];
        }
        $this->nb = 24 + $parametre->get('Noel');
        $this->ressources = new Ressource($parametre);
    }

    /** @return array<int, array{Gagnant: string, Cadeau: string, Illustration?: string}> */
    public function getResultats(): array
    {
        if (
            !file_exists(
                $this->ressources->getFichier(
                    $this->ressources::FORMAT_CHEMIN,
                    'resultats',
                    $this->ressources::CAS_ORIGINAL
                )
            )
        ) {
            $this->setTirageAuSort();
        }

        return $this->getTirageAuSort();
    }

    private function setTirageAuSort(): void
    {
        $participants = $this->extractionCSV('participants');
        $lots = $this->extractionCSV('lots');

        if ($this->nb == count($participants) && $this->nb == count($lots)) {
            //Génération des lignes pour le CSV
            $lignes = [];
            for ($i = 0; $i <= $this->nb - 1; $i++) {
                $lignes[$i] = $participants[$i] . ',' . $lots[$i];
            }

            //Création du fichier de résultats avec ces lignes
            $this->insertionCSV('resultats', $lignes);
        }
    }

    /** @return array<int, array{Gagnant: string, Cadeau: string, Illustration?: string}> */
    private function getTirageAuSort(): array
    {
        $resultats = [];

        $contenu = $this->extractionCSV('resultats');

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
    private function extractionCSV(string $clef): array
    {
        $contenu = $this->ressources->lecture($clef, $this->ressources::CAS_ORIGINAL);
        $contenu = preg_split("/\R/", $contenu); /*Transforme la chaine en tableau (\R = \r\n, \n et \r)*/
        $contenu = array_slice($contenu !== false ? $contenu : [], 1, null, false); /*Supprimer la ligne d'entête*/

        if (in_array($clef, $this->modes)) {
            shuffle($contenu);
        }

        return array_slice($contenu, 0, $this->nb, false); /*On va retourner uniquement 24 à 25 lignes*/
    }

    /** @param array<int, string> $contenu */
    private function insertionCSV(string $clef, array $contenu): void
    {
        $finLigne = "\n";
        $enTete = 'Gagnant,Cadeau,Illustration' . $finLigne;

        $this->ressources->ecriture($clef, $this->ressources::CAS_ORIGINAL, $enTete . implode($finLigne, $contenu));
    }
}
