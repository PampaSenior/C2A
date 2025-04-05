function JourChange(IdJour)
{
    const {Jour} = date_du_jour();

    if (IdJour != Jour && typeof Rafraichir === 'undefined') { /* Si on est plus le même jour et que le rechargement n'est pas en cours */
        Rafraichir = true; /* Permet d'empêcher la condition supra en définissant la variable */
        location.reload(true);
    }
}