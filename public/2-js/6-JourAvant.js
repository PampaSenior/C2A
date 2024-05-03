function ClicJour()
{
    const Couleur = 'text-danger';
    const Widget = $(event.currentTarget);
    const Bordure = Widget.data('bordure');
    const Taille = Widget.data('taille');
    const URL = Widget.data('url');

    let Debut = function () {
        Widget.prop('disabled',true);
        Widget.removeClass('AnimationOuvrable');
        if (Bordure) { //0, false, undefined, null et "" sont considérés comme faux
            Widget.addClass('bloc ' + Bordure + ' ' + Taille);
        }
        Widget.html('<i class="fa-solid fa-spinner fa-spin ' + Couleur + ' cherche ' + Taille + '"></i>');
    }

    let Succes = function (Reponse) {
        Widget.replaceWith(Reponse);
    }

    let Echec = function (Erreur) {
        Widget.html('<i class="fa-solid fa-exclamation-triangle ' + Couleur + ' erreur ' + Taille + '"></i>');
        console.log(
            "Le cadeau n'a pu être récupéré.\r\n\r\n" + 'Raison : ' + JSON.stringify(Erreur)
        );
    }

    let Fin = function () {
        Widget.prop('disabled', false);
        Widget.off('click');
    }

    $.ajax({
        url: URL,
        method: 'GET',
        dataType: 'html',
        beforeSend: Debut,
        success: Succes,
        error: Echec,
        complete: Fin
    })
}

function JourAvant(J,M)
{
    const LaDate = new Date();
    const Mois = LaDate.getMonth(); //Va de 0 à 11
    const Jours = $('[data-taille]');

    //Pour contraindre Id de 1 à nb de jours
    const JourMax = Math.min(J, Jours.length);

    if (Mois == M - 1) { //Si on est le bon mois
        for (var i = 0; i <= JourMax - 1; i++) { //Attention les indices de Jours vont de 0 à JourMax - 1
            $(Jours[i]).on('click',ClicJour); //Mise en place du clic de révélation jusqu'à aujourd'hui

            if (i < JourMax - 1 || J > Jours.length) { //Utilisation de la révélation jusqu'à la veille ou sur tous si on dépasse le dernier jour
                Jours[i].click();
            }
        }
    }
};