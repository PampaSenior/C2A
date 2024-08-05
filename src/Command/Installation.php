<?php

namespace App\Command;

use App\Service\Ressource;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
    private Ressource $ressources;

    public function __construct(ParameterBagInterface $parametre)
    {
        parent::__construct();

        $this->ressources = new Ressource($parametre);
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
        $messages = [
            "<fg=bright-red>FR: Installation du fichier échouée.\nEN : File installation failed.</>",
            "<fg=green>FR : Installation du fichier réussie.\nEN : File installation successed.</>",
        ];

        $etat = Command::SUCCESS;

        $avancement = new ProgressBar(
            $sortie,
            count($this->ressources->getFichiers($this->ressources::FORMAT_CHEMIN))
        );
        $avancement->setFormat('normal');
        $avancement->setBarWidth(10);
        $avancement->start();

        foreach ($this->ressources->getFichiers($this->ressources::FORMAT_CHEMIN) as $clef => $fichier) {
            $succes = $this->generation($clef, $fichier, $entree->getOption('dev') ? 'dev' : 'prod');

            if ($succes === false) {
                $etat = Command::FAILURE;
            }

            $sortie->write("\r");
            $sortie->writeln(
                'Installation : '
                . $fichier[$this->ressources::CAS_SAUVEGARDE]
                . ' --> '
                . $fichier[$this->ressources::CAS_ORIGINAL]
            );
            $sortie->writeln($messages[$succes]);

            $avancement->advance();
            $avancement->display();
        }

        $avancement->finish();

        return $etat;
    }

    private function secret(int $taille): string
    {
        $chaine = random_bytes(max(1, $taille));
        return bin2hex($chaine);
    }

    /** @param array{original: string, sauvegarde: string} $fichier */
    private function generation(string $clef, array $fichier, string $option): bool
    {
        $sortie = false;

        if (file_exists($fichier[$this->ressources::CAS_SAUVEGARDE])) {
            $contenu = $this->ressources->lecture(
                $clef,
                $this->ressources::CAS_SAUVEGARDE
            );

            if ($clef == 'initialisation') {
                $contenu = preg_replace(
                    '/^#([A-Z]+.*)/m',
                    '${1}',
                    $contenu
                ); /* Supprime le caractère de commentaire */
                $contenu = preg_replace(
                    '/^(APP_ENV=).*/m',
                    '${1}' . $option,
                    is_null($contenu) ? '' : $contenu
                ); /* Renseigne l'environnement */
                $contenu = preg_replace(
                    '/0{32}/',
                    $this->secret(16),
                    is_null($contenu) ? '' : $contenu
                ); /* Renseigne le secret */
            }

            $sortie = $this->ressources->ecriture(
                $clef,
                $this->ressources::CAS_ORIGINAL,
                is_null($contenu) ? '' : $contenu
            );
        }

        return $sortie;
    }
}
