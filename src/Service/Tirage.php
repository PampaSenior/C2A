<?php

namespace App\Service;

use app\Service\Application;

class Tirage
  {
  private Application $Application;

  private const FichierResultats = 'resultats.csv';

  public function __construct()
    {
    $this->Application = new Application();
    }

  public function getResultats(): array
    {
    $Dossier = '../public/'.$this->Application->getDossierDocument();

    $Fichier = $Dossier.self::FichierResultats;

    return $this->ChargementResultats($Fichier);
    }

  private function ChargementResultats(string $Chemin): array
    {
    $Resultats = [];

    $Contenu = $this->LectureCSV($Chemin);
    $Contenu = array_slice($Contenu,1,25,true); //Suppression de la 1ere ligne d'entête et d'informations qui seraient après le jour 25

    foreach ($Contenu as $Clef => $Ligne)
      {
      $Separation = explode(",",$Ligne);

      if (count($Separation) == 2 || count($Separation) == 3)
        {
        $Resultats[$Clef] = [
                            'Gagnant' => $Separation[0],
                            'Cadeau' => $Separation[1],
                            ];

        if (isset($Separation[2]) && str_replace('"','',$Separation[2]) != "")
          {
          $Resultats[$Clef]['Illustration'] = str_replace('"','',$Separation[2]);
          }
        }
      }

    return $Resultats;
    }

  private function LectureCSV(string $Fichier): array
    {
    if (file_exists($Fichier))
      {
      return file($Fichier,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      }

    return [];
    }

  }
