<?php

namespace App\Tests;

use App\Service\Application as Logiciel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class InstallationTest extends KernelTestCase
{
    private string $suffixe;
    /** @var array<string, array{Dossier:string, Source:string, Cible:string}> $chemins */
    private array $chemins;

    /**
     * Permet de vérifier la fonctionnalité d'installation automatique de l'application
     */
    public function testInstallation(): void
    {
        $this->suffixe = '.sauver';

        $application = new Logiciel();
        $dossierRacine = $application->getDossierPublic() . '../';
        $dossierDocument = $application->getDossierPublic() . $application->getDossierDocument();

        $this->chemins['Initialisation'] = [
            'Dossier' => $dossierRacine,
            'Source' => '.env',
            'Cible' => '.env.local',
        ];
        $this->chemins['Resultat'] = [
            'Dossier' => $dossierDocument,
            'Source' => 'exemple-resultats.csv',
            'Cible' => 'resultats.csv',
        ];
        $this->chemins['Participant'] = [
            'Dossier' => $dossierDocument,
            'Source' => 'exemple-participants.csv',
            'Cible' => 'participants.csv',
        ];
        $this->chemins['Lot'] = [
            'Dossier' => $dossierDocument,
            'Source' => 'exemple-lots.csv',
            'Cible' => 'lots.csv',
        ];

        $noyau = static::createKernel();
        $application = new Application($noyau);

        $command = $application->find('app:Installation');
        $commandTester = new CommandTester($command);

        //Sauvegarde des fichiers générés par l'installation déjà présents
        $this->originaux('Sauvegarder', 'Cible');

        //Cas avec l'argument pour de la production
        $commandTester->execute(['--dev' => false]);
        $this->verification($commandTester, 0, 'prod');
        $this->nettoyage();

        //Cas avec l'argument pour du developpement
        $commandTester->execute(['--dev' => true]);
        $this->verification($commandTester, 0, 'dev');
        $this->nettoyage();

        //Cas d'un fichier source absent
        foreach ($this->chemins as $fichier) {
            $document = $fichier['Dossier'] . $fichier['Source'];

            $affichageEchec = 'Installation : ' . $fichier['Source'] . ' --> ' . $fichier['Cible']
                . PHP_EOL .
                "FR: Installation du fichier échouée.\nEN : File installation failed.";

            //Sauvegarde du fichier original
            $this->original('Sauvegarder', $document);

            $commandTester->execute([]);
            $this->assertSame($commandTester->getStatusCode(), 1);
            $this->assertStringContainsString($affichageEchec, $commandTester->getDisplay());
            $this->nettoyage();

            //Rétablissement du fichier original
            $this->original('Retablir', $document);
        }

        //Rétablissement des fichiers générés par l'installation déjà présents
        $this->originaux('Retablir', 'Cible');
    }

    private function nettoyage(): void
    {
        foreach ($this->chemins as $fichier) {
            $chemin = $fichier['Dossier'] . $fichier['Cible'];
            if (file_exists($chemin)) {
                unlink($chemin);
            }
        }
    }

    private function originaux(string $sens, string $contexte): void
    {
        foreach ($this->chemins as $fichier) {
            $document = $fichier['Dossier'] . $fichier[$contexte];
            $this->original($sens, $document);
        }
    }

    private function original(string $sens, string $fichier): void
    {
        $cible = $fichier;
        $source = $cible . $this->suffixe;

        if (strtolower($sens) == 'sauvegarder') { //Permet d'autoriser "Sauvegarder" et "sauvegarder"
            $source = $fichier;
            $cible = $source . $this->suffixe;
        }

        if (file_exists($source)) {
            rename($source, $cible);
        }
    }

    private function verification(CommandTester $commande, int $sortie, string $environnement): void
    {
        //Vérification de la sortie de la commande
        $this->assertSame($commande->getStatusCode(), $sortie);
        //Vérification de la génération des fichiers
        foreach ($this->chemins as $fichier) {
            $fichierCible = $fichier['Dossier'] . $fichier['Cible'];
            $this->assertFileExists($fichierCible);
        }
        //Vérification du contenu de .env.local
        $contenu = file_get_contents($this->chemins['Initialisation']['Cible']);
        $this->assertStringContainsString('APP_ENV=' . $environnement, $contenu === false ? '' : $contenu);
        $this->assertMatchesRegularExpression('/^APP_SECRET=[0-9a-f]{32}$/m', $contenu === false ? '' : $contenu);
    }
}
