<?php

namespace App\Service;

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

    private string $dossierPublic;
    private string $dossierStyle;
    private string $dossierScript;
    private string $dossierImage;
    private string $dossierPolice;
    private string $dossierDocument;

    public function __construct()
    {
        $this->prenomAuteur = "Pampa";
        $this->nomAuteur = "Senior";

        $this->nom = "Calendrier de l'avent";
        $this->acronyme = "C2A";
        $this->icone = "Hache.png";
        $this->description = "Un calendrier de l'avent possédant une très grande liberté de configuration";
        $this->style = "Jour";

        $this->versionDate = "13/11/2023";
        $this->versionMajeur = "1";
        $this->versionMineur = "0";
        $this->versionCorrectif = "3";
        $this->versionPHP = phpversion();
        $this->versionSymfony = \Symfony\Component\HttpKernel\Kernel::VERSION;

        $this->dossierPublic = __DIR__ . '/../../public/';
        $this->dossierStyle = '1-css/';
        $this->dossierScript = '2-js/';
        $this->dossierImage = '3-images/';
        $this->dossierPolice = '4-polices/';
        $this->dossierDocument = '5-documents/';
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

    public function getDossierPublic(): string
    {
        return $this->dossierPublic;
    }

    public function getDossierStyle(): string
    {
        return $this->dossierStyle;
    }

    public function getDossierScript(): string
    {
        return $this->dossierScript;
    }

    public function getDossierImage(): string
    {
        return $this->dossierImage;
    }

    public function getDossierPolice(): string
    {
        return $this->dossierPolice;
    }

    public function getDossierDocument(): string
    {
        return $this->dossierDocument;
    }
}
