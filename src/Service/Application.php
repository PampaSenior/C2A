<?php

namespace App\Service;

class Application
  {
  private $PrenomAuteur;
  private $NomAuteur;

  private $NomApplication;
  private $AcronymeApplication;
  private $IconeApplication;
  private $DescriptionApplication;
  private $StyleApplication;

  private $VersionDate;
  private $VersionMajeur;
  private $VersionMineur;
  private $VersionCorrectif;
  private $VersionPHP;
  private $VersionSymfony;

  private $DossierStyle;
  private $DossierScript;
  private $DossierImage;
  private $DossierPolice;
  private $DossierDocument;

  public function __construct()
    {
    $this->PrenomAuteur = "Pampa";
    $this->NomAuteur = "Senior";

    $this->NomApplication = "Calendrier de l'avent";
    $this->AcronymeApplication = "C2A";
    $this->IconeApplication = "Hache.png";
    $this->DescriptionApplication = "Un calendrier de l'avent possédant une très grande liberté de configuration";
    $this->StyleApplication = "Jour";

    $this->VersionDate = "31/10/2022";
    $this->VersionMajeur = "1";
    $this->VersionMineur = "0";
    $this->VersionCorrectif = "2";
    $this->VersionPHP = phpversion();
    $this->VersionSymfony = \Symfony\Component\HttpKernel\Kernel::VERSION;

    $this->DossierStyle = '1-css/';
    $this->DossierScript = '2-js/';
    $this->DossierImage = '3-images/';
    $this->DossierPolice = '4-polices/';
    $this->DossierDocument = '5-documents/';
    }

  public function getPrenomAuteur(): string {return $this->PrenomAuteur;}
  public function getNomAuteur(): string {return $this->NomAuteur;}
  public function getAuteur(): string {return $this->getPrenomAuteur()." ".$this->getNomAuteur();}

  public function getNomApplication(): string {return $this->NomApplication;}
  public function getAcronymeApplication(): string {return $this->AcronymeApplication;}
  public function getApplication(): string {return $this->getAcronymeApplication()." : ".$this->getNomApplication();}
  public function getIconeApplication(): string {return $this->IconeApplication;}
  public function getDescriptionApplication(): string {return $this->DescriptionApplication;}
  public function getStyleApplication(): string {return $this->StyleApplication;}

  public function getVersionDate(): string {return $this->VersionDate;}
  public function getVersionMajeur(): string {return $this->VersionMajeur;}
  public function getVersionMineur(): string {return $this->VersionMineur;}
  public function getVersionCorrectif(): string {return $this->VersionCorrectif;}
  public function getVersion(): string {return $this->getVersionMajeur().".".$this->getVersionMineur().".".$this->getVersionCorrectif();}
  public function getVersionPHP(): string {return $this->VersionPHP;}
  public function getVersionSymfony(): string {return $this->VersionSymfony;}

  public function getDossierStyle(): string {return $this->DossierStyle;}
  public function getDossierScript(): string {return $this->DossierScript;}
  public function getDossierImage(): string {return $this->DossierImage;}
  public function getDossierPolice(): string {return $this->DossierPolice;}
  public function getDossierDocument(): string {return $this->DossierDocument;}
  }
