<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Permet de vérifier la page d'administration
 */
class AdministrationTest extends WebTestCase
{
    public function testAdministration(): void
    {
        $client = static::createClient(); //Générer un navigateur fictif
        $client->request('GET', '/Administration');

        $this->assertResponseIsSuccessful();
    }
}
