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
3. Créer le fichier de configuration
   ```sh
   cp .env .env.local
   ```
4. Éditer la configuration
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
- «TITRE_MODALE» représente le titre de la fenêtre pour l'oeuf de pâques
- «TEXTE_MODALE» représente le texte dans la fenêtre pour l'oeuf de pâques
- «COULEUR_FOND» représente la couleur hexadécimale de l'arrière plan
- «COULEUR_TEXTE» représente la couleur hexadécimale du texte
- «NOEL» représente le fait d'afficher où non le 25 décembre
- «NEIGE» représente la neige à afficher
- «FORME» représente le placement des éléments du calendrier (grille ou sapin)
- «STYLE» représente l'aspect graphique des éléments du calendrier (cadeau ou image)
- «TAILLE» représente la place que doit prendre les éléments du calendrier (xs, lg, xl)
- «POT_2_MIEL» représente le résultat à afficher en cas de tricherie
- «RESULTATS» contient les données des gagnants ainsi que de leur cadeau

Il suffit de décommenter les lignes de chacune de ces variables.
Elles sont déjà pré-remplies avec des exemples.

<p align="right">(<a href="#top-fr">retour en haut &#129045;</a>)</p>

<h3 id="feuille-route">Feuille de route</h3>

- Ajouter d'autres modèles de calendrier
- Moderniser la police d'écriture
- Tirer au sort le gagnant et son cadeau

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
3. Create configuration
   ```sh
   cp .env .env.local
   ```
4. Edit configuration
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
- «TITRE_MODALE» is the texte for the title of the easter egg
- «TEXTE_MODALE» is the texte for the window of the easter egg
- «COULEUR_FOND» is the hexadecimal color of the background
- «COULEUR_TEXTE» is the hexadecimal color of the texte
- «NOEL» is for showing the christmas day
- «NEIGE» is for the snow to display
- «FORME» is for the shape of the calendar (grid or christmas tree)
- «STYLE» is for the days graphism in the calendar (font or picture)
- «TAILLE» is the size of the calendar (xs, lg, xl)
- «POT_2_MIEL» is an honeypot for hacker
- «RESULTATS» is the datas for winners and gifts

You must uncomment every lines for theses variables. They have been written with exemples.

<p align="right">(<a href="#top-en">back to the top &#129045;</a>)</p>

<h3 id="roadmap">Roadmap</h3>

- Add calendar templates
- Modernize font
- Winner and gift random generation

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
Better snow tutorial [FR] : 
<ol>
  <li>
    <a href="https://www.creativejuiz.fr/blog/css-css3/creer-une-animation-de-neige-ou-de-particules-en-css3">Michaël Crofte</a>
  </li>
  <li>
    <a href="https://www.creativejuiz.fr/blog/doc/snow-animation-css3/">Preview</a>
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