<?php

namespace App\Service;

use App\Service\Application;

class Tirage
  {
  private array $Chemins;

  public function __construct()
    {
    $this->Application = new Application();
    $Dossier = '../public/'.$this->Application->getDossierDocument();

    $Fichiers = [
                'Resultats' => 'resultats.csv',
                'Participants' => 'participants.csv',
                'Lots' => 'lots.csv'
                ];
    foreach ($Fichiers as $Clef => $Fichier)
      {
      $this->Chemin[$Clef] = $Dossier.$Fichier;
      }
    }

  public function getResultats(): array
    {
    $Fichier = $this->Chemin['Resultats'];

    if (!file_exists($Fichier))
      {
      $this->TirageAuSort();
      }

    return $this->ChargementResultats();
    }

  private function TirageAuSort(): void
    {
    $Contenu = $this->LectureCSV($this->Chemin['Participants']);
    $Participants = array_slice($Contenu,1,null,true); //Suppression uniquement de la 1ere ligne d'entête

    $Contenu = $this->LectureCSV($this->Chemin['Lots']);
    $Lots = array_slice($Contenu,1,25,true); //Suppression de la 1ere ligne d'entête et on borne à 25 jours donc lignes

    if ($Participants != [] && $Lots != [])
      {
      $NbElements = min(count($Participants),count($Lots));

      $Gagnants = $Participants;
      $Cadeaux = $Lots;
      $Cas = 0;

      if (count($Participants)>count($Lots))
        {
        $Gagnants = array_rand($Participants,$NbElements);
        $Cas = 1;
        }
      elseif (count($Participants)<count($Lots))
        {
        $Cadeaux = array_rand($Lots,$NbElements);
      $Cas = 2;
        }

      $Tableau = [];
      for ($i = 1; $i <= min($NbElements,25); $i++)
        {
        switch ($Cas)
          {
          case 0 :
            $Tableau[$i] = $Gagnants[$i].','.$Cadeaux[$i];
            break;
          case 1 :
            $Tableau[$i] = $Participants[$Gagnants[$i-1]].','.$Cadeaux[$i];
            break;
          case 2 :
            $Tableau[$i] = $Gagnants[$i].','.$Lots[$Cadeaux[$i-1]];
            break;
          }
        }

      shuffle($Tableau);

      $Resultats = 'Gagnant,Cadeau,Illustration';
      foreach ($Tableau as $Element)
        {
        $Resultats = $Resultats."\n".$Element;
        }

      file_put_contents($this->Chemin['Resultats'],$Resultats,LOCK_EX);

      }

    }

  private function ChargementResultats(): array
    {
    $Resultats = [];

    $Contenu = $this->LectureCSV($this->Chemin['Resultats']);
    $Contenu = array_slice($Contenu,1,25,true); //Suppression de la 1ere ligne d'entête et on borne à 25 jours donc lignes

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
