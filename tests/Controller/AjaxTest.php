<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AjaxTest extends WebTestCase
{
    /**
     * Permet de vérifier la page d'api
     */
    public function testResultat(): void
    {
        $client = static::createClient(); //Générer un navigateur fictif

        for ($i = 1; $i <= 30; $i++) {
            $client->request('GET', '/Ajax/Resultat/' . $i);

            $code = 200;
            if ($i > 25) {
                $code = 404;
            }

            $this->assertEquals($code, $client->getResponse()->getStatusCode());
        }

        rename('.env.local', '.env.local.save');
        $indexation = $client->request('GET', '/Ajax/Resultat/1');
        $this->assertStringContainsString(
            $indexation->filter('#fr')->text(),
            'Un problème de configuration a été détecté. Le fichier ".env.local" n\'existe pas.'
        );
        $this->assertStringContainsString(
            $indexation->filter('#en')->text(),
            'A problem of configuration was detected. The file ".env.local" doesn\'t exists.'
        );
        rename('.env.local.save', '.env.local');
    }
}
