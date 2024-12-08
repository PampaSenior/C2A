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
    private string $secret;
    private string $mot2passe;

    public function __construct(ParameterBagInterface $parametre)
    {
        parent::__construct();

        $this->ressources = new Ressource($parametre);
        $this->secret = $this->genererSecret(16);
        $this->mot2passe = $this->genererMdp(15);
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

        $sortie->writeln('');
        $sortie->writeln('-----Mot de passe/Password-----');
        $sortie->writeln($this->mot2passe);

        return $etat;
    }

    private function genererSecret(int $taille): string
    {
        $chaine = random_bytes(max(1, $taille));
        return bin2hex($chaine);
    }

    private function genererMdp(int $taille): string
    {
        $chiffres = '0123456789';
        $minuscules = 'abcdefghijklmnopqrstuvwxyz';
        $majuscules = strtoupper($minuscules);
        /* Éviter {}() et $ pour empêcher ${} ou $() dans une chaine en "" */
        /* Les caractères €£°µàâäéèêëîïôöùûÿçÀÂÄÉÈÊËÎÏÔÖÙÛŸÇ nécessitent mb_{fonction} */
        $speciaux = '<>,;:!%_@^.|?*+-[]';

        $mdpaleatoire = '';
        for ($i = 0; $i < max(1, $taille); $i++) {
            $categorie = random_int(0, 3);
            switch ($categorie) {
                case 0:
                    $position = random_int(0, strlen($chiffres) - 1);
                    $mdpaleatoire .= substr($chiffres, $position, 1);
                    break;
                case 1:
                    $position = random_int(0, strlen($minuscules) - 1);
                    $mdpaleatoire .= substr($minuscules, $position, 1);
                    break;
                case 2:
                    $position = random_int(0, strlen($majuscules) - 1);
                    $mdpaleatoire .= substr($majuscules, $position, 1);
                    break;
                case 3:
                    $position = random_int(0, strlen($speciaux) - 1);
                    $mdpaleatoire .= substr($speciaux, $position, 1);
                    break;
            }
        }

        return str_shuffle($mdpaleatoire);
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
                    '/^#([A-Z].*)$/m',
                    '${1}',
                    $contenu
                ); /* Supprime le caractère de commentaire */
                $contenu = preg_replace(
                    '/^(APP_ENV=).*$/m',
                    '${1}' . $option,
                    is_null($contenu) ? '' : $contenu
                ); /* Renseigne l'environnement */
                $contenu = preg_replace(
                    '/^(APP_SECRET=).*$/m',
                    '${1}' . $this->secret,
                    is_null($contenu) ? '' : $contenu
                ); /* Renseigne le secret */
                $contenu = preg_replace(
                    '/^(MOT_2_PASSE=).*$/m',
                    '${1}' . '"' . $this->mot2passe . '"',
                    is_null($contenu) ? '' : $contenu
                ); /* Renseigne le mot de passe */
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
