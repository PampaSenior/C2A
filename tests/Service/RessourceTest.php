<?php

namespace App\Tests;

use App\Service\Ressource;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Permet de vérifier la fonctionnalité d'utilisation des ressources de l'application
 */
class RessourceTest extends WebTestCase
{
    public function testRessource(): void
    {
        $client = static::createClient(); //Générer un navigateur fictif
        $parametre = $client->getContainer()->get(ParameterBagInterface::class); // Récupération d'un service

        $ressources = new Ressource($parametre);

        //Pour vérifier les fonctions sur les dossiers
        foreach ($ressources->getDossiers($ressources::FORMAT_CHEMIN) as $dossier) {
            $this->assertFileExists($dossier);
        }

        $this->assertSame($ressources->getDossiers('echec'), []);
        $this->assertSame($ressources->getDossier('echec', 'initialisation'), '');
        $this->assertSame($ressources->getDossier($ressources::FORMAT_CHEMIN, 'echec'), '');

        //Pour vérifier les fonctions sur les fichiers
        foreach ($ressources->getFichiers($ressources::FORMAT_CHEMIN) as $fichiers) {
            foreach ($fichiers as $fichier) {
                $this->assertFileExists($fichier);
            }
        }

        $this->assertSame($ressources->getFichiers('echec'), []);
        $this->assertSame($ressources->getFichier('echec', 'initialisation', $ressources::CAS_ORIGINAL), '');
        $this->assertSame($ressources->getFichier($ressources::FORMAT_CHEMIN, 'echec', $ressources::CAS_ORIGINAL), '');
        $this->assertSame($ressources->getFichier($ressources::FORMAT_CHEMIN, 'initialisation', 'echec'), '');
    }
}
