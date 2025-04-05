function date_du_jour()
{
    const date_actuelle = new Date();

    return {
        Jour: date_actuelle.getDate(), /* Va de 1 à 31 */
        Mois: date_actuelle.getMonth(), /* Va de 0 à 11 */
        Annee: date_actuelle.getFullYear() /* Année complète et pas sur 2 éléments */
    }
}

/* Pour le cookie js */
function jeton_clic(Clef)
{
    const {Jour, Mois, Annee} = date_du_jour();

    return {
        Clef: Clef,
        Valeur: Jour + '-' + Mois + '-' + Annee,
    }
}

/* Pour l'usage du son */
function joue_son()
{
    son = document.getElementById('son');
    if (son) { /* 0, false, undefined, null et "" sont considérés comme faux */
        son.play();
    } else {
        console.log('/!\\ Aucun son disponible.');
    }
}