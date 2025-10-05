<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Permet de vérifier les pages d'administration
 */
class AdministrationTest extends WebTestCase
{
    public function testAdministration(): void
    {
        $client = static::createClient(); /* Générer un navigateur fictif */
        $client->request('GET', '/Administration');

        $this->assertResponseIsSuccessful();
    }

    public function testConfiguration(): void
    {
        $lesCas = ['Outil' , 'Graphisme', 'Surprise', 'Fichier'];

        $client = static::createClient(); /* Générer un navigateur fictif */

        foreach ($lesCas as $cas) {
            $client->request('GET', '/Administration/Configuration/' . $cas);

            $this->assertResponseIsSuccessful();
        }

        // Pour tester le "requirements" de la route
        $client->request('GET', '/Administration/Configuration/Test');

        $this->assertResponseStatusCodeSame(404);
    }
}
