<?php

namespace App\Tests;

use App\Service\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationTest extends WebTestCase
{
    private Application $application;

    /**
     * Permet de vérifier la fonctionnalité de configuration de l'application
     */
    public function testApplication(): void
    {
        $this->application = new Application();

        $fichiers = [
            $this->application->getDossierStyle(),
            $this->application->getDossierScript(),
            $this->application->getDossierImage(),
            $this->application->getDossierPolice(),
            $this->application->getDossierDocument(),
        ];

        $client = static::createClient(); //Générer un navigateur fictif
        $indexation = $client->request('GET', '/');

        $liens = $indexation->filter('link');
        foreach ($liens as $style) {
            $fichiers[] = substr($style->getAttribute('href'), 1); /*On enlève le slash initial*/ /*@phpstan-ignore method.notFound (faux positif)*/ /*phpcs:ignore Generic.Files.LineLength*/
        }

        $scriptes = $indexation->filter('script');
        foreach ($scriptes as $scripte) {
            $fichiers[] = substr($scripte->getAttribute('src'), 1); /*On enlève le slash initial*/ /*@phpstan-ignore method.notFound (faux positif)*/ /*phpcs:ignore Generic.Files.LineLength*/
        }

        foreach ($fichiers as $fichier) {
            $this->assertFileExists($this->application->getDossierPublic() . $fichier);
        }

        $this->assertMatchesRegularExpression('/\d+\.\d+\.\d+/', $this->application->getVersionPHP());
        $this->assertMatchesRegularExpression('/\d+\.\d+\.\d+/', $this->application->getVersionSymfony());
    }
}
