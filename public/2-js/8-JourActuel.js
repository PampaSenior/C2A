function JourActuel(IdJour)
{
    const Jour = document.getElementById(IdJour);

    const {Clef, Valeur} = jeton_clic('JourClic');

    if (localStorage.getItem(Clef) == null || localStorage.getItem(Clef) != Valeur) {
        $(Jour).addClass('AnimationOuvrable');
        $(Jour).on('click', function () {
            joue_son();
            localStorage.setItem(Clef, Valeur); /* Mise en place du clic de sauvegarde de la révélation */
        });
    } else {
        Jour.click(); /* Utilisation de la révélation */
    }
}