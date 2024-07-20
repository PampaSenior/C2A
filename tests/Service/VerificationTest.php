<?php

namespace App\Tests;

use App\Service\Ressource;
use App\Service\Verification;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class VerificationTest extends KernelTestCase
{
    private Ressource $ressources;

    /**
     * Permet de vérifier la fonctionnalité de vérification de l'application
     */
    public function testVerification(): void
    {
        self::bootKernel();

        $parametre = static::getContainer()->get(ParameterBagInterface::class); // Récupération d'un service
        $this->ressources = new Ressource($parametre);

        //Cas nominal
        $this->verification($parametre, true, '');

        //Cas 1 sans le .env.local
        $this->original('sauvegarder', 'initialisation');
        $this->verification($parametre, false, 'Information.Configuration.Fichier');
        $this->original('retablir', 'initialisation');

        //Cas 2 sans une variable de configuration du .env.test.local (impossible à tester)
        //$fichier = '.env.test.local';
        //$sauvegarde = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        //$element = array_pop($sauvegarde);
        //file_put_contents($fichier, implode("\n", $sauvegarde), LOCK_EX);
        //$this->verification($parametre, false, 'Information.Configuration.Variable');

        //array_push($sauvegarde, $element);
        //file_put_contents($fichier, implode("\n", $sauvegarde), LOCK_EX);

        //Cas 3-A sans resultats.csv (nominal car participants.csv et lots.csv)
        $this->original('sauvegarder', 'resultats');
        $this->verification($parametre, true, '');
        $this->original('retablir', 'resultats');

        //Cas 3-B sans participants.csv (nominal car resultats.csv)
        $this->original('sauvegarder', 'participants');
        $this->verification($parametre, true, '');
        $this->original('retablir', 'participants');

        //Cas 3-C sans lots.csv (nominal car resultats.csv)
        $this->original('sauvegarder', 'lots');
        $this->verification($parametre, true, '');
        $this->original('retablir', 'lots');

        //Cas 3-D sans participants.csv et lots.csv (nominal car resultats.csv)
        $this->originaux('sauvegarder', ['participants', 'lots']);
        $this->verification($parametre, true, '');
        $this->originaux('retablir', ['participants', 'lots']);

        //Cas 3-E sans resultats.csv et participants.csv
        $this->originaux('sauvegarder', ['resultats', 'participants']);
        $this->verification($parametre, false, 'Information.Configuration.Tirage');
        $this->originaux('retablir', ['resultats', 'participants']);

        //Cas 3-F sans resultats.csv et lots.csv
        $this->originaux('sauvegarder', ['resultats', 'lots']);
        $this->verification($parametre, false, 'Information.Configuration.Tirage');
        $this->originaux('retablir', ['resultats', 'lots']);

        //Cas 3-G sans aucun fichier pour le tirage
        $this->originaux('sauvegarder', ['resultats', 'participants', 'lots']);
        $this->verification($parametre, false, 'Information.Configuration.Tirage');
        $this->originaux('retablir', ['resultats', 'participants', 'lots']);
    }

    private function originaux(string $sens, array $clefs): void
    {
        foreach ($clefs as $clef) {
            $this->original($sens, $clef);
        }
    }

    private function original(string $sens, string $clef): void
    {
        $suffixe = '.sauver';

        $cible = $this->ressources->getFichier(
            $this->ressources::FORMAT_CHEMIN,
            $clef,
            $this->ressources::CAS_ORIGINAL
        );
        $source = $cible . $suffixe;

        if (strtolower($sens) == 'sauvegarder') { //Permet d'autoriser "Sauvegarder" et "sauvegarder"
            $source = $this->ressources->getFichier(
                $this->ressources::FORMAT_CHEMIN,
                $clef,
                $this->ressources::CAS_ORIGINAL
            );
            $cible = $source . $suffixe;
        }

        if (file_exists($source)) {
            rename($source, $cible);
        }
    }

    private function verification(ParameterBagInterface $parametre, bool $valide, string $alerte): void
    {
        $verification = new Verification($parametre);
        $this->assertTrue($verification->isValide() === $valide);
        $this->assertStringContainsString($alerte, $verification->getErreur());
    }
}
