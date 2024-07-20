<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AjaxTest extends WebTestCase
{
    /**
     * Permet de vérifier la page d'api
     */
    public function testAccueil(): void
    {
        $client = static::createClient(); //Générer un navigateur fictif

        for ($i = 1; $i <= 30; $i++) {
            $client->request('GET', '/Ajax/Cadeau/' . $i);

            $code = 200;
            if ($i > 25) {
                $code = 404;
            }

            $this->assertEquals($code, $client->getResponse()->getStatusCode());
        }
    }
}
