<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Permet de vérifier la page d'api des résultats
 */
class AjaxTest extends WebTestCase
{
    public function testResultat(): void
    {
        $client = static::createClient(); //Générer un navigateur fictif

        $liens = ['HTML','JSON'];

        for ($i = 1; $i <= 30; $i++) {
            foreach ($liens as $lien) {
                $client->request('GET', '/Ajax/' . $lien . '/Resultat/' . $i);

                $code = 200;
                if ($i > 25) {
                    $code = 404;
                }

                $this->assertEquals($code, $client->getResponse()->getStatusCode());
            }
        }
    }

    public function testConfigurationKO(): void
    {
        $client = static::createClient(); //Générer un navigateur fictif

        $messages = [
            'fr' => 'Un problème de configuration a été détecté. Le fichier ".env.local" n\'existe pas.',
            'en' => 'A problem of configuration was detected. The file ".env.local" doesn\'t exists.',
        ];

        rename('.env.local', '.env.local.sauver');

        $indexation = $client->request('GET', '/Ajax/HTML/Resultat/1');
        $this->assertStringContainsString($indexation->filter('#fr')->text(), $messages['fr']);
        $this->assertStringContainsString($indexation->filter('#en')->text(), $messages['en']);

        $client->request('GET', '/Ajax/JSON/Resultat/1');
        $reponse = json_encode($messages, JSON_HEX_QUOT | JSON_HEX_APOS);
        $this->assertEquals($client->getResponse()->getContent(), $reponse);

        rename('.env.local.sauver', '.env.local');
    }
}
