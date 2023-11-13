<?php

namespace App\Tests;

use App\Service\Application;
use App\Service\Tirage;

class TirageTest extends \PHPUnit\Framework\TestCase
  {
  private Application $Application;
  private array $Chemins;
  private array $CSV;

  /**
   * Permet de vérifier la fonctionnalité de tirage au sort
   */
  public function testTirage(): void
    {
    // /!\ Le __construct() est déconseillé pour PhpUnit
    $this->Application = new Application();

    $Dossier = '../public/'.$this->Application->getDossierDocument();

    $Fichiers = [
                'Resultats' => 'resultats.csv',
                'Participants' => 'participants.csv',
                'Lots' => 'lots.csv'
                ];

    foreach ($Fichiers as $Clef => $Fichier)
      {
      $this->Chemins[$Clef] = $Dossier.$Fichier;
      $this->CSV[$Clef] = '';
      }

    //Hack pour faire fonctionner les liens relatifs pendant les tests
    chdir('public');

    //Cas où "participants" possède moins d'éléments que "lots"
    $this->GenerationDesCas(
                           "Participant\n1\n2",
                           "Cadeau,Illustration\nA,J1.png\nB\nC,J3.png\nD,"
                           );

    //Cas où "participants" possède autant d'éléments que "lots"
    $this->GenerationDesCas(
                           "Participant\n1\n2\n3\n4",
                           "Cadeau,Illustration\nA,J1.png\nB\nC,J3.png\nD,"
                           );

    //Cas où "participants" possède plus d'éléments que "lots"
    $this->GenerationDesCas(
                           "Participant\n1\n2\n2\n4\n5",
                           "Cadeau,Illustration\nA,J1.png\nB\nC,J3.png\nD,"
                           );
    }

  private function GenerationDesCas(string $DonneesParticipants, string $DonneesLots): void
    {
    //Stockage des données des fichiers qui pourraient déjà être présents
    $this->TraitementCSV('Lecture');

    //Suppression des fichiers qui pourraient déjà être présents
    $this->TraitementCSV('Suppression');

    //Mettre les données de tests dans les fichiers
    file_put_contents($this->Chemins['Participants'],$DonneesParticipants,LOCK_EX);
    file_put_contents($this->Chemins['Lots'],$DonneesLots,LOCK_EX);

    //Réaliser la génération des résultats
    $Tirage = new Tirage();
    $LesResultats = $Tirage->getResultats();

    //Tester la génération du fichier
    $this->assertFileExists($this->Chemins['Resultats']);

    //Suppression des fichiers de test générés
    $this->TraitementCSV('Suppression');

    //Remettre les données d'origine dans les fichiers
    $this->TraitementCSV('Ecriture');
    }

  private function TraitementCSV(string $Choix): void
    {
    foreach ($this->Chemins as $Clef => $Chemin)
      {
      switch ($Choix)
        {
        case 'Lecture':
          $Contenu = file($Chemin,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
          $this->CSV[$Clef] = implode("\n",$Contenu);
          break;
        case 'Suppression':
          unlink($Chemin);
          break;
        case 'Ecriture':
          file_put_contents($Chemin,$this->CSV[$Clef],LOCK_EX);
          break;
        }
      }
    }
  }
