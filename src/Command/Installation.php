<?php

namespace App\Command;

use App\Service\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

#[AsCommand(
    name: 'Installation',
    description: 'FR : Installation automatique de l\'application.\nEN : Automatic application installation.',
    hidden: false,
    aliases: ['app:Installation']
)]
class Installation extends Command
{
    /** @var array<string, array{Dossier:string, Source:string, Cible:string}> $chemins */
    private array $chemins;
    /** @var array<int, string> $messages */
    private array $messages;
    /** @var array<int, bool> $echecs */
    private array $echecs;

    public function __construct()
    {
        parent::__construct();

        $application = new Application();
        $dossierRacine = $application->getDossierPublic() . '../';
        $dossierDocument = $application->getDossierPublic() . $application->getDossierDocument();

        $this->chemins['Initialisation'] = [
            'Dossier' => $dossierRacine,
            'Source' => '.env',
            'Cible' => '.env.local'
        ];
        $this->chemins['Resultat'] = [
            'Dossier' => $dossierDocument,
            'Source' => 'exemple-resultats.csv',
            'Cible' => 'resultats.csv'
        ];
        $this->chemins['Participant'] = [
            'Dossier' => $dossierDocument,
            'Source' => 'exemple-participants.csv',
            'Cible' => 'participants.csv'
        ];
        $this->chemins['Lot'] = [
            'Dossier' => $dossierDocument,
            'Source' => 'exemple-lots.csv',
            'Cible' => 'lots.csv'
        ];

        $this->messages = [
            "<fg=green>FR : Installation du fichier réussie.\nEN : File installation successed.</>",
            "<fg=bright-red>FR: Installation du fichier échouée.\nEN : File installation failed.</>",
        ];
    }

    protected function configure(): void
    {
        $this->setHelp("FR : Installe l'application.\nEN : Install the app.");
        $this->addOption(
            '--dev',
            null,
            InputOption::VALUE_NONE,
            "FR : Installation de l'application pour du developpement.\nEN : Install the app for development.",
            null
        );
    }

    protected function execute(InputInterface $entree, OutputInterface $sortie): int
    {

        $avancement = new ProgressBar($sortie, count($this->chemins));
        $avancement->setFormat('normal');
        $avancement->setBarWidth(10);
        $avancement->start();

        foreach ($this->chemins as $clef => $fichier) {
            $this->echecs[] = $this->generation($clef, $fichier, $entree->getOption('dev') ? 'dev' : 'prod');

            $sortie->write("\r");
            $sortie->writeln('Installation : ' . $fichier['Source'] . ' --> ' . $fichier['Cible']);
            $sortie->writeln($this->messages[end($this->echecs) === true]);

            $avancement->advance();
            $avancement->display();
        }

        $avancement->finish();

        foreach ($this->echecs as $echec) {
            if ($echec) {
                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }

    private function secret(int $taille): string
    {
        $chaine = random_bytes(max(1, $taille));
        return bin2hex($chaine);
    }

    /** @param array{Dossier:string, Source:string, Cible:string} $fichier */
    private function generation(string $clef, array $fichier, string $option): bool
    {
        $succes = false;

        $fichierSource = $fichier['Dossier'] . $fichier['Source'];
        $fichierCible = $fichier['Dossier'] . $fichier['Cible'];

        if (file_exists($fichierSource)) {
            $contenu = file_get_contents($fichierSource);

            if ($clef == 'Initialisation') {
                $contenu = preg_replace(
                    '/^#([A-Z]+.*)/m',
                    '${1}',
                    $contenu === false ? '' : $contenu
                ); //Supprime le caractère de commentaire
                $contenu = preg_replace(
                    '/0{32}/',
                    $this->secret(16),
                    is_null($contenu) ? '' : $contenu
                ); //Renseigne le secret
                $contenu = preg_replace(
                    '/^(APP_ENV=).*/m',
                    '${1}' . $option,
                    is_null($contenu) ? '' : $contenu
                ); /*Renseigne l'environnement*/
            }

            $succes = file_put_contents($fichierCible, $contenu, LOCK_EX);
        }

        return $succes === false;
    }
}
