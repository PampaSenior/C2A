function JourSpecial(J, M)
{
    const LaDate = new Date();
    const Jour = LaDate.getDate(); //Va de 1 à 31
    const Mois = LaDate.getMonth(); //Va de 0 à 11 donc décembre = 11
    const Annee = LaDate.getFullYear(); //Année complète et pas sur 2 éléments

    //Pour le cookie js
    const Clef = 'JourSpecial';
    const Valeur = Jour + '-' + Mois + '-' + Annee;

    if (Jour == J && Mois == M) {
        if (localStorage.getItem(Clef) == null || localStorage.getItem(Clef) != Valeur) {
            $('#' + Clef).show();
            localStorage.setItem(Clef, Valeur); //On sauvegarde ce jour en cookie
            //localStorage.removeItem(Clef); //Pour supprimer le cookie js
        }
    }
}