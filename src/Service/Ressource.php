<?php

namespace App\Service;

use App\Service\Application;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Ressource
{
    public const FORMAT_URL = 'url';
    public const FORMAT_CHEMIN = 'chemin';
    public const CAS_ORIGINAL = 'original';
    public const CAS_SAUVEGARDE = 'sauvegarde';
    /** @var array{url: array<string, string>, chemin: array<string, string>} $dossiers */
    private array $dossiers;
    /** @var array{url: array<string, array{original: string, sauvegarde: string}>, chemin: array<string, array{original: string, sauvegarde: string}>} $fichiers */
    private array $fichiers;

    public function __construct(ParameterBagInterface $parametre)
    {
        $application = new Application();

        $dossierPrincipal = $parametre->get('kernel.project_dir') . DIRECTORY_SEPARATOR;
        $dossierPublic = $dossierPrincipal . 'public' . DIRECTORY_SEPARATOR;

        foreach ($application->getDossiers() as $clef => $dossier) {
            $this->dossiers[self::FORMAT_URL][$clef] = $dossier . '/';
            $this->dossiers[self::FORMAT_CHEMIN][$clef] = $dossierPublic . $dossier . DIRECTORY_SEPARATOR;
        }

        foreach ($application->getFichiers() as $clef => $fichier) {
            $prefixe = 'exemple-';

            $url = [
              self::CAS_ORIGINAL => $this->getDossier(self::FORMAT_URL, 'documents') . $fichier,
              self::CAS_SAUVEGARDE => $this->getDossier(self::FORMAT_URL, 'documents') . $prefixe . $fichier,
            ];

            $chemin = [
              self::CAS_ORIGINAL => $this->getDossier(self::FORMAT_CHEMIN, 'documents') . $fichier,
              self::CAS_SAUVEGARDE => $this->getDossier(self::FORMAT_CHEMIN, 'documents') . $prefixe . $fichier,
            ];

            if ($clef == 'initialisation') {
                $suffixe = '.local';

                $url = [
                  self::CAS_ORIGINAL => $fichier,
                  self::CAS_SAUVEGARDE => str_replace($suffixe, '', $fichier),
                ];

                $chemin = [
                  self::CAS_ORIGINAL => $dossierPrincipal . $fichier,
                  self::CAS_SAUVEGARDE => $dossierPrincipal . str_replace($suffixe, '', $fichier),
                ];
            }

            $this->fichiers[self::FORMAT_URL][$clef] = $url;
            $this->fichiers[self::FORMAT_CHEMIN][$clef] = $chemin;
        }
    }


    /** @return array<string, string> */
    public function getDossiers(string $format): array
    {
        if (array_key_exists($format, $this->dossiers)) {
            return $this->dossiers[$format];
        }

        return [];
    }

    public function getDossier(string $format, string $clef): string
    {
        if (
            array_key_exists($format, $this->dossiers) &&
            array_key_exists($clef, $this->dossiers[$format])
        ) {
            return $this->dossiers[$format][$clef];
        }

        return '';
    }

    /** @return array<string, array{original: string, sauvegarde: string}> */
    public function getFichiers(string $format): array
    {
        if (array_key_exists($format, $this->fichiers)) {
            return $this->fichiers[$format];
        }

        return [];
    }

    public function getFichier(string $format, string $clef, string $cas): string
    {
        if (
            array_key_exists($format, $this->fichiers) &&
            array_key_exists($clef, $this->fichiers[$format]) &&
            array_key_exists($cas, $this->fichiers[$format][$clef])
        ) {
            return $this->fichiers[$format][$clef][$cas];
        }

        return '';
    }

    public function lecture(string $clef, string $cas): string
    {
        $chemin = $this->getFichier(self::FORMAT_CHEMIN, $clef, $cas);

        if (file_exists($chemin)) {
            $contenu = file_get_contents($chemin);
        }

        return (isset($contenu) && $contenu !== false) ? $contenu : '';
    }

    public function ecriture(string $clef, string $cas, string $contenu): bool
    {
        $chemin = $this->getFichier(self::FORMAT_CHEMIN, $clef, $cas);

        if ($chemin !== '') {
            $resultat = file_put_contents($chemin, $contenu, LOCK_EX);
        }

        return (isset($resultat) && $resultat !== false) ? true : false;
    }
}
