[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![MIT License][license-shield]][license-url]

<div align="center">
  <a href="https://github.com/PampaSenior/C2A">
    <img src="public\3-images\Hache.png" alt="Logo" width="32" height="32">
  </a>

  <p align="center">
    <a href="#fr">Français</a>
    ·
    <a href="#en">English</a>
  </p>
</div>

<div id="top-fr"></div>

<div align="center">
  <h3 id="fr" align="center">
    C2A : Calendrier de l'avent
  </h3>

  <p align="center">
    Ce projet vous permet de mettre en place un calendrier de l'avent très rapidement et simplement.
    Ainsi, chaque jour, les utilisateurs pourront dévoiler un gagnant et son cadeau en cliquant
    sur le jour actuel.
    <br />
    <br />
    <a href="#configuration-fr"><strong>Documentation</strong></a>
    <br />
    <br />
    <a href="https://github.com/PampaSenior/C2A/issues">Indiquer un bug</a>
    ·
    <a href="https://github.com/PampaSenior/C2A/pulls">Demander une amélioration</a>
  </p>
</div>

<h3>Sommaire</h3>

<details>
  <ol>
    <li>
      <a href="#presentation">Présentation</a>
    </li>
    <li>
      <a href="#technologies">Technologies</a>
    </li>
    <li>
      <a href="#installation-fr">Installation</a>
    </li>
    <li>
      <a href="#configuration-fr">Configuration</a>
    </li>
    <li>
      <a href="#resultats">Résultats</a>
      <ul>
        <li>
          <a href="#externe">Tirage externe</a>
        </li>
        <li>
          <a href="#interne">Tirage interne</a>
        </li>
      </ul>
    </li>
    <li>
      <a href="#feuille-route">Feuille de route</a>
    </li>
    <li>
      <a href="#license-fr">License</a>
    </li>
    <li>
      <a href="#contacts-fr">Contacts</a>
    </li>
    <li>
      <a href="#remerciements">Remerciements</a>
    </li>
    <li>
      <a href="#complements">Compléments</a>
    </li>
  </ol>
</details>

<h3 id="presentation">Présentation</h3>

![aperçu-1]
![aperçu-2]

Ce calendrier de l'avent a été développé afin de laisser une grande
liberté de présentation à ceux qui souhaiteraient s'en servir.
En effet, les choix possibles sont nombreux :
- la forme (en grille ou en sapin);
- le style (affichage prédéterminé ou aléatoire d'images);
- la neige (absence, boule ou flocon);
- la capacité de mettre le 25 décembre ou non;
- la taille des éléments du calendrier;
- le texte du titre et de l'oeuf de pâques;
- la couleur du fond et de la police du titre;

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="technologies">Technologies</h3>

* [Php V8.0.0](https://www.php.net/)
* [Composer V2.1.12](https://getcomposer.org/)
* [Symfony V5.4.0](https://symfony.com/)
* [jQuery V3.6.0](https://jquery.com)
* [Bootstrap V5.1.1](https://getbootstrap.com)
* [Font Awesome V5.15.4](https://fontawesome.com/)

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="installation-fr">Installation</h3>

1. Importer le projet
   ```sh
   git clone https://github.com/PampaSenior/C2A.git
   ```
2. Installer les dépendances
   ```sh
   cd C2A/
   composer update
   ```
3. Installer l'application
- En production
   ```sh
   php bin/console Installation
   ```
- En développement
   ```sh
   php bin/console Installation --dev
   ```
4. Éditer la configuration (optionnel)
   ```sh
   nano .env.local
   ```
5. Démarrer le projet
   ```sh
   php -S localhost:8000 -t public
   ```

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="configuration-fr">Configuration</h3>

Tout se passe dans le .env.local généré pendant la phase d'installation.
- «APP_ENV» représente l'environnement de l'application (dev ou prod)
- «APP_SECRET» représente la chaine hexadecimale secrète de symfony
- «TITRE» représente le texte au dessus du calendrier
- «TITRE_CUPIDON» represente le titre de la modale pour la St valentin
- «TEXTE_CUPIDON» represente le texte de la modale pour la St valentin
- «TITRE_POISSON» represente le titre de la modale pour le 1er avril
- «TEXTE_POISSON» represente le texte de la modale pour le 1er avril
- «TITRE_CADEAU» represente le titre de la modale pour Noël
- «TEXTE_CADEAU» represente le texte de la modale pour Noël
- «COULEUR_FOND» représente la couleur hexadécimale de l'arrière plan
- «COULEUR_TEXTE» représente la couleur hexadécimale du texte
- «NOEL» représente le fait d'afficher où non le 25 décembre
- «NEIGE» représente la neige à afficher
- «FORME» représente le placement des éléments du calendrier (grille ou sapin)
- «STYLE» représente l'aspect graphique des éléments du calendrier (cadeau ou image)
- «BORDURE» représente l'aspect des bordures de chaque élément (aucune, carrée, arrondie ou ronde)
- «TAILLE» représente la place que doit prendre les éléments du calendrier (sm, md, lg ou xl)
- «POT_2_MIEL» représente le résultat à afficher en cas de tricherie

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="resultats">Résultats</h3>

Il existe 2 options pour renseigner les résultats :
- soit renseigner un fichier csv avec pour chaque jour le gagnant, le cadeau et
optionnellement l'illustration qui se nommera «resultats.csv». On parlera ici
de tirage externe puisque les résultats sont générés en dehors de l'application.
- soit renseigner deux fichiers csv avec pour l'un, nommé «participants.csv»,
la liste des participants, et dans l'autre, nommé «lots.csv»,
les cadeaux disponibles avec optionnellement le nom de la photo qui y correspond.
On parlera ici de tirage interne puisque c'est l'application qui se chargera de
générer l'attribution des lots à certain participants.

Peut importe l'option choisie, le ou les csv sont présents dans le dossier
«C2A/public/5-documents». Un exemple de chaque y a été mis pour aider. Ils sont
préfixé par le terme «exemple-».

De plus, les images d'illustration des cadeaux sont à mettre dans le dossier
«C2A/public/3-image».

Et enfin, la ligne d'entête qui explique les colonnes dans chaque csv ne doit pas
être supprimée.

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

- <h4 id="externe">Tirage externe</h4>

Le csv «resultats.csv» doit contenir entre 24 et 25 lignes, chacune représentant un jour,
ayant de 2 à 3 colonnes séparées par une «,».
- La première colonne sera le nom et prénom du gagnant
- La seconde colonne sera le nom du cadeau
- La troisième colonne, qui est optionnelle, sera le nom du fichier image correspondant
au cadeau

Exemple de lignes dans ce fichier sans image d'illustration pour le cadeau :

Dupond Dupont,Fusée  
Avril Septembre,tibia

Exemple de lignes dans ce fichier avec image d'illustration pour le cadeau :

Dupond Dupont,Fusée,fusee.png  
Avril Septembre,tibia,os.png

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

- <h4 id="interne">Tirage interne</h4>

Dans cette seconde option, l'application se chargera de générer l'attribution
des lots à des participants en créant «resultats.csv» lors du premier accès à
l'application grâce aux fichiers «participants.csv» et «lots.csv».

Le csv «participants.csv» ne doit contenir qu'une colonne sans limite sur le nombre
de ligne et chacune représentera un participant

Exemple de lignes dans ce fichier :

Dupond Dupont  
Avril Septembre

Le csv «lots.csv» doit contenir entre 24 et 25 lignes de 1 à 2 colonnes séparées par une «,».
- La première colonne sera le nom du cadeau
- La seconde colonne, qui est optionnelle, sera le nom du fichier image correspondant
au cadeau

Exemple de lignes dans ce fichier sans image d'illustration pour le cadeau :

Fusée  
os

Exemple de lignes dans ce fichier avec image d'illustration pour le cadeau :

Fusée,fusee.png  
tibia,os.png

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="feuille-route">Feuille de route</h3>

- Ajouter d'autres modèles de calendrier
- Moderniser la police d'écriture
- Mettre des feux d'artifices pour les modales de jours spéciaux

 --> Des idées sont les bienvenues

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="license-fr">License</h3>

La permission est accordée par la présente, gratuitement, à chaque personne
obtenant une copie de ce logiciel et de sa documentation associée d'utiliser,
copier, modifier, fusionner, publier et distribuer le code à condition
d'indiquer un moyen permettant d'accéder à la source originale de celui-ci,
à savoir : https://github.com/PampaSenior/C2A

Il n'est pas autorisé de percevoir de l'argent par l'utilisation ou la vente
de ce logiciel sans reverser 1% des profits a/aux auteur(s).

Dans le cas où du code ou des ressources seraient modifiées ou ajoutées à ce
logiciel, elles devront rester libre, c'est à dire communicable, dans un
format lisible à toute personne en faisant la demande.

Cette licence permettant d'expliciter les droits d'auteur doit être incluse
dans toutes les copies même partielle de cette application sans subir de
modifications.

Ce logiciel est fourni sans garantie d'aucune sorte, expressément ou implicitement,
incluant, sans pour autant s'y limiter, aux garanties de qualité marchande, de non
contrefaçon ou d'aptitude à un usage particulier.

En aucun cas, le ou les auteurs ou titulaires des droits d'auteur ne pourront être
tenus pour responsable notamment pour des dommages moraux, physiques ou
financiers, directs ou indirects, ou pour des actions contractuelles ou délictuelles
résultant de l'utilisation ou non de ce logiciel.

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="contacts-fr">Contacts</h3>

Courriel de contact : [pampa.senior.code@gmail.com](mailto:pampa.senior.code@gmail.com)
<br />
Lien du projet : [https://github.com/PampaSenior/C2A](https://github.com/PampaSenior/C2A)

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="remerciements">Remerciements</h3>

Modèle du Readme.md : <a href="https://github.com/othneildrew/Best-README-Template">othneildrew</a>
<br />
Image pour la neige : <a href="https://pixabay.com/vectors/snowflake-christmas-winter-flake-29366/">Clker-Free-Vector-Images</a>
<br />
Image pour les jours : <a href="https://pixabay.com/vectors/advent-advent-calendar-printable-4623596/">pinwhalestock</a>
<br />
Police de noël : <a href="https://www.dafont.com/fr/kingthings-christmas.font">Kevin King</a>
<br />
Drapeau des langues : <a href="https://fr.m.wikipedia.org">Wikipedia</a>

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="complements">Compléments</h3>

Autres images pour les jours : 
<ol>
  <li>
    <a href="https://pixabay.com/vectors/advent-advent-calendar-printable-4623521/">pinwhalestock</a>
  </li>
  <li>
    <a href="https://pixabay.com/fr/vectors/av%c3%a8nement-calendrier-de-l-avent-4623597/">pinwhalestock</a>
  </li>
</ol>
Tutoriel pour une meilleure neige [FR] : 
<ol>
  <li>
    <a href="https://www.creativejuiz.fr/blog/css-css3/creer-une-animation-de-neige-ou-de-particules-en-css3">Michaël Crofte</a>
  </li>
  <li>
    <a href="https://www.creativejuiz.fr/blog/doc/snow-animation-css3/">Aperçu</a>
  </li>
</ol>
Tutoriel pour les feux d'artifices [FR] :
<ol>
  <li>
    <a href="https://copyfuture.com/blogs-details/202202010753010075">我想养只猫</a>
  </li>
</ol>

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<div id="top-en"></div>

<div align="center">
  <h3 id="en" align="center">
    C2A : Advent calendar
  </h3>

  <p align="center">
    This project let you install an advent calendar very quickly and easily.
    Hence, everyday, users will discover a winner and his gift by clicking on the actual day.
    <br />
    <br />
    <a href="#configuration-en"><strong>Documentation</strong></a>
    <br />
    <br />
    <a href="https://github.com/PampaSenior/C2A/issues">Report a bug</a>
    ·
    <a href="https://github.com/PampaSenior/C2A/pulls">Request a Feature</a>
  </p>
</div>

<h3>Table of Contents</h3>

<details>
  <ol>
    <li>
      <a href="#about">About</a>
    </li>
    <li>
      <a href="#dependencies">Dependencies</a>
    </li>
    <li>
      <a href="#installation-en">Installation</a>
    </li>
    <li>
      <a href="#configuration-en">Configuration</a>
    </li>
    <li>
      <a href="#results">Results</a>
      <ul>
        <li>
          <a href="#external">External drawing of lot</a>
        </li>
        <li>
          <a href="#internal">Internal drawing of lot</a>
        </li>
      </ul>
    </li>
    <li>
      <a href="#roadmap">Roadmap</a>
    </li>
    <li>
      <a href="#license-en">License</a>
    </li>
    <li>
      <a href="#contacts-en">Contacts</a>
    </li>
    <li>
      <a href="#acknowledgments">Acknowledgments</a>
    </li>
    <li>
      <a href="#addons">Addons</a>
    </li>
  </ol>
</details>

<h3 id="about">About</h3>

![aperçu-1]
![aperçu-2]

This advent calendar was developed in order to let you a hudge liberty of customisation.
Hence, your have plenty of choices :
- shape (grid or christmas tree);
- style (font static, font random or picture for gifts);
- snow (none, snowball, snowflake);
- adding the christmas day or not;
- size of the calendar;
- string for the title and the easter egg;
- background color and font color for the title;

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="dependencies">Dependencies</h3>

* [Php V8.0.0](https://www.php.net/)
* [Composer V2.1.12](https://getcomposer.org/)
* [Symfony V5.4.0](https://symfony.com/)
* [jQuery V3.6.0](https://jquery.com)
* [Bootstrap V5.1.1](https://getbootstrap.com)
* [Font Awesome V5.15.4](https://fontawesome.com/)

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="installation-en">Installation</h3>

1. Clone the repository
   ```sh
   git clone https://github.com/PampaSenior/C2A.git
   ```
2. Install packages
   ```sh
   cd C2A/
   composer update
   ```
3. Install the app
- In production
   ```sh
   php bin/console Installation
   ```
- In developement
   ```sh
   php bin/console Installation --dev
   ```
4. Edit configuration (optionnal)
   ```sh
   nano .env.local
   ```
5. Run the project
   ```sh
   php -S localhost:8000 -t public
   ```

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="configuration-en">Configuration</h3>

All you need is to configure the .env.local generate during the installation.
- «APP_ENV» is the software environnement (dev or prod)
- «APP_SECRET» is the secret hexadecimal string for symfony
- «TITRE» is the texte for the title above the calendar
- «TITRE_CUPIDON» is the text for the title of the easter egg for valentine's day
- «TEXTE_CUPIDON» is the text for the text of the easter egg for valentine's day
- «TITRE_POISSON» is the text for the title of the easter egg for the joke day
- «TEXTE_POISSON» is the text for the text of the easter egg for the joke day
- «TITRE_CADEAU» is the text for the title of the easter egg for christmas
- «TEXTE_CADEAU» is the text for the text of the easter egg for christmas
- «COULEUR_FOND» is the hexadecimal color of the background
- «COULEUR_TEXTE» is the hexadecimal color of the texte
- «NOEL» is for showing the christmas day
- «NEIGE» is for the snow to display
- «FORME» is for the shape of the calendar (grid or christmas tree)
- «STYLE» is for the days graphism in the calendar (font or picture)
- «BORDURE» is for the border for every day (none, square, rounded or circle)
- «TAILLE» is the size of the calendar (sm, md, lg or xl)
- «POT_2_MIEL» is an honeypot for hacker

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="results">Results</h3>

There is 2 options in order to inform the results :
- either inform into a csv file named «resultats.csv», with for everyday, the winner, the gift
and optionally a picture. We call this option the external drawing of lot because results are
created outside the application.
- either inform into two csv file, the first one named «participants.csv» containing participants
and the second one named «lots.csv» containing available gifts with optionally the name of the linked
picture. We call this option the internal drawing of lot because results are
created by the application by associate a gift to a winner.

Regardless of the chosen option, all the csv files are in the folder «C2A/public/5-documents».
An example of each are present inside this folder for help. They are prefixed with the
term «exemple-».

Moreover, images for gifts must be in the folder «C2A/public/3-image».

And finally, the first line in every csv which explain the column must not be deleted.

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

- <h4 id="external">External drawing of lot</h4>

The csv «resultats.csv» must contain between 24 and 25 lines, everyone account for a day,
with 2 or 3 columns separated by «,».
- The first column will be the name and the surname of the winner
- The second column will be the name of the gift
- The third column, which is optional, will be the name of the image linked to the gift

Exemple of lines in this file without any image for illustrate the gift :

Dupond Dupont,Rocket  
April September,shinbone

Exemple of lines in this file with an image for illustrate the gift :

Dupond Dupont,Rocket,rocket.png  
April September,shinbone,bone.png

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

- <h4 id="interne">Internal drawing of lot</h4>

In this second option, the application will associate gifts to participants by generating
the «resultats.csv» file using «participants.csv» and «lots.csv» ones during the first
access by the web browser.

The csv «participants.csv» must contain only one column without any limit for the line
number and everyone account for a participant.

Exemple of lines in this file :

Dupond Dupont  
April September

The csv «lots.csv» must contain between 24 and 25 lines with 1 or 2 colums separated by «,».
- The first column will be the name of the gift
- The second column, which is optional, will be the name of the image linked to the gift

Exemple of lines in this file without any image for illustrate the gift :

Rocket  
bone

Exemple of lines in this file with an image for illustrate the gift :

Rocket,rocket.png  
shinbone,bone.png

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="roadmap">Roadmap</h3>

- Add calendar templates
- Modernize font
- Use fireworks in modal for special days

--> Give ideas

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="license-en">License</h3>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files, to use, copy, modify,
merge, publish, distribute the code provided by giving a way to access to
the original code, for exemple : https://github.com/PampaSenior/C2A

It is not allowed to earn money by using or selling this software without
reverse 1% of the profit to the author.

In the case the code would be changes or ressources would be added in this
software, they must remain free. It's meaning transmissible in a
readable format to any asking people.

This license which inform about copyright and permissions must be included
in all copies or substantial portions of the Software without modifications.

The software is provided without warranty of any kind, express or implied
including but not limited to the warranties of merchantability,
noninfringement or fitness for a particular purpose.

In no event shall the authors or copyright holders be liable for any claim,
damages or other liability, whether in an action of contract, tort or
otherwise, arising from, out of or in connection with the software or the use
in the software.

The authentic version is the french one not the english translation.

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="contacts-en">Contacts</h3>

Email : [pampa.senior.code@gmail.com](mailto:pampa.senior.code@gmail.com)
<br />
Project : [https://github.com/PampaSenior/C2A](https://github.com/PampaSenior/C2A)

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="acknowledgments">Acknowledgments</h3>

Readme.md template : <a href="https://github.com/othneildrew/Best-README-Template">othneildrew</a>
<br />
Snow picture : <a href="https://pixabay.com/vectors/snowflake-christmas-winter-flake-29366/">Clker-Free-Vector-Images</a>
<br />
Daily pictures : <a href="https://pixabay.com/vectors/advent-advent-calendar-printable-4623596/">pinwhalestock</a>
<br />
Christmas font : <a href="https://www.dafont.com/fr/kingthings-christmas.font">Kevin King</a>
<br />
Languages flag : <a href="https://fr.m.wikipedia.org">Wikipedia</a>

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="addons">Addons</h3>

Others daily pictures : 
<ol>
  <li>
    <a href="https://pixabay.com/vectors/advent-advent-calendar-printable-4623521/">pinwhalestock</a>
  </li>
  <li>
    <a href="https://pixabay.com/fr/vectors/av%c3%a8nement-calendrier-de-l-avent-4623597/">pinwhalestock</a>
  </li>
</ol>
Snow tutorial [FR] :
<ol>
  <li>
    <a href="https://www.creativejuiz.fr/blog/css-css3/creer-une-animation-de-neige-ou-de-particules-en-css3">Michaël Crofte</a>
  </li>
  <li>
    <a href="https://www.creativejuiz.fr/blog/doc/snow-animation-css3/">Preview</a>
  </li>
</ol>
Fireworks tutorial [FR] :
<ol>
  <li>
    <a href="https://copyfuture.com/blogs-details/202202010753010075">我想养只猫</a>
  </li>
</ol>

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

[contributors-shield]: https://img.shields.io/github/contributors/PampaSenior/C2A.svg?style=for-the-badge
[contributors-url]: https://github.com/PampaSenior/C2A/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/PampaSenior/C2A.svg?style=for-the-badge
[forks-url]: https://github.com/PampaSenior/C2A/network/members
[stars-shield]: https://img.shields.io/github/stars/PampaSenior/C2A.svg?style=for-the-badge
[stars-url]:https://github.com/PampaSenior/C2A/stargazers
[issues-shield]: https://img.shields.io/github/issues/PampaSenior/C2A.svg?style=for-the-badge
[issues-url]: https://github.com/PampaSenior/C2A/issues
[license-shield]: https://img.shields.io/github/license/PampaSenior/C2A.svg?style=for-the-badge
[license-url]: https://github.com/PampaSenior/C2A/blob/master/LICENSE.txt
[aperçu-1]: https://github.com/PampaSenior/Illustrations/blob/maitre/C2A_1.png
[aperçu-2]: https://github.com/PampaSenior/Illustrations/blob/maitre/C2A_2.png