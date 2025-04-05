<?php

namespace App\Entity;

/** @SuppressWarnings(PHPMD.TooManyFields) */
class Application
{
    private string $prenomAuteur;
    private string $nomAuteur;

    private string $nom;
    private string $acronyme;
    private string $icone;
    private string $description;
    private string $style;

    private string $versionDate;
    private string $versionMajeur;
    private string $versionMineur;
    private string $versionCorrectif;
    private string $versionPHP;
    private string $versionSymfony;

    /** @var array<string, string> $dossiers */
    private array $dossiers;

    /** @var array<string, string> $fichiers */
    private array $fichiers;

    /** @var array<string, array{'type': string, 'nom': string}> $parametres */
    private array $parametres;

    public function __construct()
    {
        $this->prenomAuteur = "Pampa";
        $this->nomAuteur = "Senior";

        $this->nom = "Calendrier de l'avent";
        $this->acronyme = "C2A";
        $this->icone = "Hache.png";
        $this->description = "Un calendrier de l'avent possédant une très grande liberté de configuration";
        $this->style = "Jour";

        $this->versionDate = "06/08/2024";
        $this->versionMajeur = "1";
        $this->versionMineur = "0";
        $this->versionCorrectif = "4";
        $this->versionPHP = phpversion();
        $this->versionSymfony = \Symfony\Component\HttpKernel\Kernel::VERSION;

        $this->dossiers = [
            'styles' => '1-css',
            'scriptes' => '2-js',
            'images' => '3-images',
            'polices' => '4-polices',
            'documents' => '5-documents',
            'musiques' => '6-musiques',
        ];

        $this->fichiers = [
            'initialisation' => '.env.local',
            'lots' => 'lots.csv',
            'participants' => 'participants.csv',
            'resultats' => 'resultats.csv',
        ];

        $this->parametres = [
            'Mot2Passe' => ['type' => 'string', 'nom' => 'MOT_2_PASSE'],
            'Titre' => ['type' => 'string', 'nom' =>  'TITRE'],
            'TitreNouvelAn' => ['type' => 'string', 'nom' =>  'TITRE_NOUVEL_AN'],
            'TexteNouvelAn' => ['type' => 'string', 'nom' =>  'TEXTE_NOUVEL_AN'],
            'TitreCupidon' => ['type' => 'string', 'nom' =>  'TITRE_CUPIDON'],
            'TexteCupidon' => ['type' => 'string', 'nom' =>  'TEXTE_CUPIDON'],
            'TitrePoisson' => ['type' => 'string', 'nom' =>  'TITRE_POISSON'],
            'TextePoisson' => ['type' => 'string', 'nom' =>  'TEXTE_POISSON'],
            'TitreHorreur' => ['type' => 'string', 'nom' =>  'TITRE_HORREUR'],
            'TexteHorreur' => ['type' => 'string', 'nom' =>  'TEXTE_HORREUR'],
            'TitreCadeau' => ['type' => 'string', 'nom' =>  'TITRE_CADEAU'],
            'TexteCadeau' => ['type' => 'string', 'nom' =>  'TEXTE_CADEAU'],
            'CouleurFond' => ['type' => 'string', 'nom' =>  'COULEUR_FOND'],
            'CouleurTexte' => ['type' => 'string', 'nom' =>  'COULEUR_TEXTE'],
            'Noel' => ['type' => 'bool', 'nom' =>  'NOEL'],
            'Audio' => ['type' => 'bool', 'nom' =>  'AUDIO'],
            'Neige' => ['type' => 'int', 'nom' =>  'NEIGE'],
            'Forme' => ['type' => 'int', 'nom' =>  'FORME'],
            'Style' => ['type' => 'int', 'nom' =>  'STYLE'],
            'Bordure' => ['type' => 'int', 'nom' =>  'BORDURE'],
            'Zoom' => ['type' => 'int', 'nom' =>  'ZOOM'],
            'Taille' => ['type' => 'string', 'nom' =>  'TAILLE'],
            'Tirage' => ['type' => 'int', 'nom' =>  'TIRAGE'],
            'Pot2Miel' => ['type' => 'json', 'nom' =>  'POT_2_MIEL'],
        ];
    }

    public function getPrenomAuteur(): string
    {
        return $this->prenomAuteur;
    }

    public function getNomAuteur(): string
    {
        return $this->nomAuteur;
    }

    public function getAuteur(): string
    {
        return $this->getPrenomAuteur() . " " . $this->getNomAuteur();
    }

    public function getNomApplication(): string
    {
        return $this->nom;
    }

    public function getAcronyme(): string
    {
        return $this->acronyme;
    }

    public function getApplication(): string
    {
        return $this->getAcronyme() . " : " . $this->getNomApplication();
    }

    public function getIcone(): string
    {
        return $this->icone;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function getVersionDate(): string
    {
        return $this->versionDate;
    }

    public function getVersionMajeur(): string
    {
        return $this->versionMajeur;
    }

    public function getVersionMineur(): string
    {
        return $this->versionMineur;
    }

    public function getVersionCorrectif(): string
    {
        return $this->versionCorrectif;
    }

    public function getVersion(): string
    {
        return $this->getVersionMajeur() . "." . $this->getVersionMineur() . "." . $this->getVersionCorrectif();
    }

    public function getVersionPHP(): string
    {
        return $this->versionPHP;
    }

    public function getVersionSymfony(): string
    {
        return $this->versionSymfony;
    }

    /** @return array<string, string> */
    public function getDossiers(): array
    {
        return $this->dossiers;
    }

    /** @return array<string, string> */
    public function getFichiers(): array
    {
        return $this->fichiers;
    }

    /** @return array<string, array{'type': string, 'nom': string}> */
    public function getParametres(): array
    {
        return $this->parametres;
    }
}
