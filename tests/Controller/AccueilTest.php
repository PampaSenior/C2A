<?php

namespace App\Tests;

use App\Service\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AccueilTest extends WebTestCase
{
    /**
     * Permet de vérifier la page d'accueil
     */
    public function testAccueil(): void
    {
        $application = new Application();

        $client = static::createClient(); //Générer un navigateur fictif
        $parametre = $client->getContainer()->get(ParameterBagInterface::class); // Récupération d'un service
        $indexation = $client->request('GET', '/');

        $ressources = [];

        // Ne marche pas en cas de route avec alias.
        // Cependant un alias dépend du serveur et pas de symfony
        $liens = $indexation->filter('link');
        foreach ($liens as $style) {
            $chemin = str_replace('/', DIRECTORY_SEPARATOR, $style->getAttribute('href')); /*Mettre le séparateur de l'OS à la place du '/' de l'URL*/ /*phpcs:ignore Generic.Files.LineLength*/
            $ressources[] = substr($chemin, 1); /*On enlève le slash initial*/ /*@phpstan-ignore method.notFound (faux positif)*/ /*phpcs:ignore Generic.Files.LineLength*/
        }

        // Ne marche pas en cas de route avec alias.
        // Cependant un alias dépend du serveur et pas de symfony
        $scriptes = $indexation->filter('script');
        foreach ($scriptes as $scripte) {
            if (!empty($scripte->getAttribute('src'))) { // Les scriptes sans balises 'src'
                $chemin = str_replace('/', DIRECTORY_SEPARATOR, $scripte->getAttribute('src')); /*Mettre le séparateur de l'OS à la place du '/' de l'URL*/ /*phpcs:ignore Generic.Files.LineLength*/
                $ressources[] = substr($chemin, 1); /*On enlève le slash initial*/ /*@phpstan-ignore method.notFound (faux positif)*/ /*phpcs:ignore Generic.Files.LineLength*/
            }
        }

        foreach ($ressources as $ressource) {
            $public = $parametre->get('kernel.project_dir') . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
            $this->assertFileExists($public . $ressource);
        }

        $infos = [
            $application->getStyle(),
            $application->getAuteur(),
            $application->getApplication(),
            $application->getDescription(),
            $application->getVersionDate(),
            $application->getVersion(),
            $application->getVersionPHP(),
            $application->getVersionSymfony(),
        ];

        $metadonnees = $indexation->filter('meta');
        foreach ($metadonnees as $metadonnee) {
            if (!empty($metadonnee->getAttribute('content'))) { // Les metadonnées sans contenu pour 'content'
                $this->assertEquals(array_shift($infos), $metadonnee->getAttribute('content'));
            }
        }

        $this->assertMatchesRegularExpression('/[0-9]{2}\/[0-9]{2}\/[0-9]+/', $application->getVersionDate());
        $this->assertMatchesRegularExpression('/[0-9]+\.[0-9]+\.[0-9]+/', $application->getVersion());
        $this->assertMatchesRegularExpression('/[0-9]+\.[0-9]+\.[0-9]+/', $application->getVersionPHP());
        $this->assertMatchesRegularExpression('/[0-9]+\.[0-9]+\.[0-9]+/', $application->getVersionSymfony());

        rename('.env.local', '.env.local.save');
        $indexation = $client->request('GET', '/');
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
