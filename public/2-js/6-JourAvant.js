function ClicJour()
{
    const Couleur = 'text-danger';
    const Widget = $(event.currentTarget);
    const Style = Widget.data('style');
    const Bordure = Widget.data('bordure');
    const Zoom = Widget.data('zoom');
    const Taille = Widget.data('taille');
    const URL = Widget.data('url');

    let Debut = function () {
        Widget.prop('disabled',true);
        Widget.removeClass('AnimationOuvrable');
        if (Style == 2) {
            Widget.addClass('bloc ' + Bordure + ' ' + Taille);
        }
        Widget.html('<i class="fa-solid fa-spinner fa-spin ' + Couleur + ' cherche ' + Taille + '"></i>');
    }

    let Succes = function (Reponse) {
        if (Reponse.fr || Reponse.en) { //0, false, undefined, null et "" sont considérés comme faux
            Echec(Reponse.en + " " + Reponse.fr); //La vérification de l'application a échouée
            return;
        }

        let Titre = '';
        let Texte = '';

        if (Taille == 'sm') {
            Titre = Reponse.Gagnant + "\n" + Reponse.Cadeau;
            Texte = Reponse.Gagnant + '<br/>' + Reponse.Cadeau;
        } else if (Taille == 'md') {
            Titre = 'Gagnant : ' + Reponse.Gagnant + "\nCadeau : " + Reponse.Cadeau;
            Texte = 'Gagnant :<br/>' + Reponse.Gagnant + '<br/>Cadeau :<br/>' + Reponse.Cadeau;
        } else if (Taille == 'lg') {
            Titre = 'Le gagnant : ' + Reponse.Gagnant + "\nLe cadeau : " + Reponse.Cadeau;
            Texte = 'Le gagnant :<br/>' + Reponse.Gagnant + '<br/>Le cadeau :<br/>' + Reponse.Cadeau;
        } else {
            Titre = 'Le gagnant est : ' + Reponse.Gagnant + "\nLe cadeau est : " + Reponse.Cadeau;
            Texte = 'Le gagnant est :<br/>' + Reponse.Gagnant + '<br/>Le cadeau est :<br/>' + Reponse.Cadeau;
        }

        if (Zoom == 1) {
            position = 'position-absolute top-0 start-0';
        } else if (Zoom == 2) {
            position = 'position-absolute top-0 start-50';
        } else if (Zoom == 3) {
            position = 'position-absolute top-0 start-100';
        } else if (Zoom == 4) {
            position = 'position-absolute top-50 start-0';
        } else if (Zoom == 5) {
            position = 'position-absolute top-50 start-100';
        } else if (Zoom == 6) {
            position = 'position-absolute top-100 start-0';
        } else if (Zoom == 7) {
            position = 'position-absolute top-100 start-50';
        } else if (Zoom == 8) {
            position = 'position-absolute top-100 start-100';
        }

        Widget.attr('title', Titre);
        Widget.addClass('position-relative');
        Widget.html('<span class="d-block text-truncate ' + Couleur + '">' + Texte + '</span>');

        if (Reponse.Illustration) { //0, false, undefined, null et "" sont considérés comme faux
            Widget.attr('style', 'background-image:url(' + Reponse.Illustration + ');background-size:cover;');
        }

        if (Zoom != 0) {
            Widget.append('<div><button type="button" class="py-2 ' + position + ' translate-middle badge bg-danger border-0" onclick="ClicLoupe()"><i class="fa-solid fa-magnifying-glass"></i></button></div>');
            Widget.find('div button').attr('data-gagnant', Reponse.Gagnant);
            Widget.find('div button').attr('data-cadeau', Reponse.Cadeau);
        }

        if (Zoom != 0 && Reponse.Illustration) {
            Widget.find('div button').attr('data-photo', Reponse.Illustration);
        }
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
        dataType: 'json',
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
        for (var i = 0; i <= JourMax - 1; i++) { //Attention les indices de Jours vont de 0 à JourMax-1
            $(Jours[i]).on('click',ClicJour); //Mise en place du clic de révélation jusqu'à aujourd'hui

            if (i < JourMax - 1 || J > Jours.length) { //Utilisation de la révélation jusqu'à la veille ou sur tous si on dépasse le dernier jour
                Jours[i].click();
            }
        }
    }
};