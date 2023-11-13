<?php

namespace App\Command;

use App\Service\Application;

use Symfony\Component\HttpKernel\KernelInterface;

use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Helper\ProgressBar;

class InstallationCommande extends Command
  {
  //Nom de variable obligatoire
  protected static $defaultName = 'Installation';
  //Nom de variable obligatoire
  protected static $defaultDescription = "FR : Installation automatique de l'application.\nEN : Automatic application installation.";

  private array $Chemins;
  private array $Resultats;

  public function __construct()
    {
    parent::__construct();

    $this->Chemins['Environnement'] = [
                                      'Source' => '.env',
                                      'Cible' => '.env.local'
                                      ];

    $this->Application = new Application();
    $Dossier = './public/'.$this->Application->getDossierDocument();
    $this->Chemins['Tirage']['Resultat'] = [
                                           'Source' => $Dossier.'exemple-resultats.csv',
                                           'Cible' => $Dossier.'resultats.csv'
                                           ];
    $this->Chemins['Tirage']['Participant'] = [
                                              'Source' => $Dossier.'exemple-participants.csv',
                                              'Cible' => $Dossier.'participants.csv'
                                              ];
    $this->Chemins['Tirage']['Lot'] = [
                                      'Source' => $Dossier.'exemple-lots.csv',
                                      'Cible' => $Dossier.'lots.csv'
                                      ];
    }

  protected function configure(): void
    {
    $this->setHelp("FR : Installe l'application.\nEN : Install the app.");
    $this->addOption('--dev',null,InputOption::VALUE_NONE,"FR : Installation de l'application pour du developpement.\nEN : Install the app for development.",null);
    }

  protected function execute(InputInterface $Entree, OutputInterface $Sortie): int
    {
    $BarreProgression = new ProgressBar($Sortie,4);
    $BarreProgression->setFormat('normal');
    $BarreProgression->setBarWidth(10);
    $BarreProgression->start();
    $BarreProgression->display();

    $this->Environnement($Entree->getOption('dev'),$BarreProgression);
    $this->Tirage($BarreProgression);

    $BarreProgression->finish();
    $Sortie->writeln('');

    foreach ($this->Resultats as $Resultat)
      {
      $section = $Sortie->section();
      $section->writeln('Fichier : '.$Resultat[0]);

      if ($Resultat[1] === false)
        {
        $Sortie->writeln("<fg=bright-red>FR: Installation du fichier échoué.\nEN : File installation failed.</>");
        }
      else
        {
        $Sortie->writeln("<fg=green>FR : Installation du fichier réussie.\nEN : File installation successed.</>");
        }
      }

    return Command::SUCCESS;
    }

  private function Secret(int $Taille): string
    {
    $Chaine = random_bytes($Taille);
    return bin2hex($Chaine);
    }

  private function Environnement(bool $Option, ProgressBar $BarreProgression): void
    {
    $Environnement = $Option ? 'dev' : 'prod';

    $Source = $this->Chemins['Environnement']['Source'];
    $Cible = $this->Chemins['Environnement']['Cible'];

    $Erreur = false;

    if (file_exists($Source))
      {
      $Contenu = file_get_contents($Source);
      $Contenu = preg_replace('/^#([A-Z]+.*)/m', '${1}', $Contenu); //Supprimer le caractère de commentaire
      $Contenu = preg_replace('/0{32}/', $this->Secret(16), $Contenu); //Renseigner le secret
      $Contenu = preg_replace('/^(APP_ENV=).*/m', '${1}'.$Environnement, $Contenu); //Renseigner l'environnement

      $Erreur = file_put_contents($Cible,$Contenu,LOCK_EX);
      }

    $this->Resultats[] = [$Cible,$Erreur];

    $BarreProgression->advance();
    $BarreProgression->display();
    }

  private function Tirage(ProgressBar $BarreProgression): void
    {
    foreach ($this->Chemins['Tirage'] as $Chemin)
      {
      $Erreur = false;

      if (file_exists($Chemin['Source']))
        {
        $Contenu = file_get_contents($Chemin['Source']);

        $Erreur = file_put_contents($Chemin['Cible'],$Contenu,LOCK_EX);
        }

      $this->Resultats[] = [$Chemin['Cible'],$Erreur];

      $BarreProgression->advance();
      $BarreProgression->display();
      }
    }
  }