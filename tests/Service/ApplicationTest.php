<?php

namespace App\Tests;

use App\Service\Application;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationTest extends WebTestCase
  {
  private Application $Application;

  /**
   * Permet de vérifier la fonctionnalité de configuration de l'application
   */
  public function testApplication(): void
    {
    // /!\ Le __construct() est déconseillé pour PhpUnit
    $this->Application = new Application();

    $Fichiers = [
                $this->Application->getDossierStyle(),
                $this->Application->getDossierScript(),
                $this->Application->getDossierImage(),
                $this->Application->getDossierPolice(),
                $this->Application->getDossierDocument(),
                ];

    $Client = static::createClient(); //Générer un navigateur fictif
    $Crawler = $Client->request('GET', '/');

    $Liens = $Crawler->filter('link');
    foreach ($Liens as $Clef => $Style)
      {
      $Fichiers[] = $Liens->eq($Clef)->attr('href');
      }

    $Scriptes = $Crawler->filter('script');
    foreach ($Scriptes as $Clef => $Scripte)
      {
      $Fichiers[] = $Scriptes->eq($Clef)->attr('src');
      }

    foreach ($Fichiers as $Fichier)
      {
      $this->assertFileExists($this->Application->getDossierPublic() . $Fichier);
      }

    $this->assertMatchesRegularExpression('/\d+\.\d+\.\d+/', $this->Application->getVersionPHP());
    $this->assertMatchesRegularExpression('/\d+\.\d+\.\d+/', $this->Application->getVersionSymfony());
    }
  }
