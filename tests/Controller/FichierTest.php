<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Permet de vérifier la page concernant les fichiers
 */
class FichierTest extends WebTestCase
{
    public function testFichier(): void
    {
        $client = static::createClient(); /* Générer un navigateur fictif */
        $client->request('GET', '/Fichier');

        $this->assertResponseIsSuccessful();
    }
}
