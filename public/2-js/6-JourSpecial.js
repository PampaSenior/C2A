function JourSpecial()
{
    const {Clef, Valeur} = jeton_clic('JourSpecial');

    if (localStorage.getItem(Clef) == null || localStorage.getItem(Clef) != Valeur) {
        $('#' + Clef).show();
        localStorage.setItem(Clef, Valeur); // Sauvegarde le jeton en cookie js (inverse localStorage.removeItem(Clef))
    }
}