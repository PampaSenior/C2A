function Clic()
  {
  const Couleur = 'text-danger';
  const Widget = $(event.currentTarget);
  const Style = Widget.data('style');
  const Bordure = Widget.data('bordure');
  const Taille = Widget.data('taille');
  const URL = Widget.data('url');

  let Debut = function()
    {
    Widget.prop('disabled',true);
    Widget.removeClass('AnimationOuvrable');
    if (Style == 2)
      {
      Widget.addClass('bloc '+Bordure+' '+Taille);
      }
    Widget.html('<i class="fa-solid fa-spinner fa-spin '+Couleur+' cherche '+Taille+'"></i>');
    }

  let Succes = function(Reponse)
    {
    if (Reponse.fr || Reponse.en) //0, false, undefined, null et "" sont considérés comme faux
      {
      Echec(Reponse.en+" "+Reponse.fr); //La vérification de l'application a échouée
      return;
      }

    let Titre = '';
    let Texte = '';

    if (Taille == 'sm')
      {
      Titre = Reponse.Gagnant+"\n"+Reponse.Cadeau;
      Texte = Reponse.Gagnant+'<br/>'+Reponse.Cadeau;
      }
    else if (Taille == 'md')
      {
      Titre = 'Gagnant : '+Reponse.Gagnant+"\nCadeau : "+Reponse.Cadeau;
      Texte = 'Gagnant :<br/>'+Reponse.Gagnant+'<br/>Cadeau :<br/>'+Reponse.Cadeau;
      }
    else if (Taille == 'lg')
      {
      Titre = 'Le gagnant : '+Reponse.Gagnant+"\nLe cadeau : "+Reponse.Cadeau;
      Texte = 'Le gagnant :<br/>'+Reponse.Gagnant+'<br/>Le cadeau :<br/>'+Reponse.Cadeau;
      }
    else
      {
      Titre = 'Le gagnant est : '+Reponse.Gagnant+"\nLe cadeau est : "+Reponse.Cadeau;
      Texte = 'Le gagnant est :<br/>'+Reponse.Gagnant+'<br/>Le cadeau est :<br/>'+Reponse.Cadeau;
      }

    Widget.html('<span class="d-block text-truncate '+Couleur+'">'+Texte+'</span>');
    Widget.attr('title',Titre);

    if (Reponse.Illustration) //0, false, undefined, null et "" sont considérés comme faux
      {
      Widget.attr('style','background-image:url('+Reponse.Illustration+');background-size:cover;');
      }
    }

  let Echec = function(Erreur)
    {
    Widget.html('<i class="fa-solid fa-exclamation-triangle '+Couleur+' erreur '+Taille+'"></i>');
    console.log("Le cadeau n'a pu être récupéré.\r\n\r\n"+
                'Raison : ' + JSON.stringify(Erreur));
    }

  let Fin = function()
    {
    Widget.prop('disabled',false);
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

  if (Mois == M-1) //Si on est le bon mois
    {
    for (var i=0; i<=JourMax-1; i++) //Attention les indices de Jours vont de 0 à JourMax-1
      {
      $(Jours[i]).on('click',Clic); //Mise en place du clic de révélation jusqu'à aujourd'hui

      if (i < JourMax-1 || J > Jours.length) //Utilisation de la révélation jusqu'à la veille ou sur tous si on dépasse le dernier jour
        {
        Jours[i].click();
        }
      }
    }
  };