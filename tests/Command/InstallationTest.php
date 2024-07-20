<?php

namespace App\Tests;

use App\Service\Ressource;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class InstallationTest extends KernelTestCase
{
    private Ressource $ressources;
    private string $suffixe;

    /**
     * Permet de vérifier la fonctionnalité d'installation automatique de l'application
     */
    public function testInstallation(): void
    {
        $noyau = self::bootKernel();

        $parametre = static::getContainer()->get(ParameterBagInterface::class); // Récupération d'un service
        $this->ressources = new Ressource($parametre);

        $this->suffixe = '.sauver';

        $application = new Application($noyau);
        $command = $application->find('app:Installation');
        $commandTester = new CommandTester($command);

        //Sauvegarde des fichiers générés par l'installation déjà présents
        $this->originaux('sauvegarder');

        //Cas avec l'argument pour de la production
        $commandTester->execute(['--dev' => false]);
        $this->verification($commandTester, 0, 'prod');
        $this->nettoyage();

        //Cas avec l'argument pour du developpement
        $commandTester->execute(['--dev' => true]);
        $this->verification($commandTester, 0, 'dev');
        $this->nettoyage();

        //Cas d'un fichier source absent
        foreach ($this->ressources->getFichiers($this->ressources::FORMAT_CHEMIN) as $fichier) {
            $affichageEchec = 'Installation : '
                . $fichier[$this->ressources::CAS_SAUVEGARDE]
                . ' --> '
                . $fichier[$this->ressources::CAS_ORIGINAL]
                . PHP_EOL
                . "FR: Installation du fichier échouée.\nEN : File installation failed.";

            //Sauvegarde du fichier original
            $this->original('sauvegarder', $fichier[$this->ressources::CAS_SAUVEGARDE]);

            $commandTester->execute([]);
            $this->assertSame($commandTester->getStatusCode(), 1);
            $this->assertStringContainsString($affichageEchec, $commandTester->getDisplay());
            $this->nettoyage();

            //Rétablissement du fichier original
            $this->original('retablir', $fichier['sauvegarde']);
        }

        //Rétablissement des fichiers générés par l'installation déjà présents
        $this->originaux('retablir');
    }

    private function nettoyage(): void
    {
        foreach ($this->ressources->getFichiers($this->ressources::FORMAT_CHEMIN) as $fichier) {
            if (file_exists($fichier[$this->ressources::CAS_ORIGINAL])) {
                unlink($fichier[$this->ressources::CAS_ORIGINAL]);
            }
        }
    }

    private function originaux(string $sens): void
    {
        foreach ($this->ressources->getFichiers($this->ressources::FORMAT_CHEMIN) as $fichier) {
            $this->original($sens, $fichier[$this->ressources::CAS_ORIGINAL]);
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
        foreach ($this->ressources->getFichiers($this->ressources::FORMAT_CHEMIN) as $fichier) {
            $this->assertFileExists($fichier[$this->ressources::CAS_ORIGINAL]);
        }
        //Vérification du contenu de .env.local
        $contenu = file_get_contents(
            $this->ressources->getFichier(
                $this->ressources::FORMAT_CHEMIN,
                'initialisation',
                $this->ressources::CAS_ORIGINAL
            )
        );
        $this->assertStringContainsString('APP_ENV=' . $environnement, $contenu === false ? '' : $contenu);
        $this->assertMatchesRegularExpression('/^APP_SECRET=[0-9a-f]{32}$/m', $contenu === false ? '' : $contenu);
    }
}
