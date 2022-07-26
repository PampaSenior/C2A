function TexteCourt(Texte,Taille)
  {
  if (Texte.length > Taille)
    {
    return Texte.substr(0,Taille-3)+'...';
    }
  else
    {
    return Texte;
    }
  }

function Clic()
  {
  const Couleur = 'text-danger';
  const Widget = $(event.currentTarget);
  const Style = Widget.data('style');
  const Classe = Widget.data('classe');
  const URL = Widget.data('url');

  let Debut = function()
    {
    Widget.prop('disabled',true);
    Widget.removeClass('AnimationOuvrable');
    if (Style == 2)
      {
      Widget.addClass('Boule-'+Classe);
      }
    Widget.html('<i class="fa-solid fa-spinner fa-spin '+Couleur+' ChercheNoel-'+Classe+'"></i>');
    }

  let Succes = function(Reponse)
    {
    let Titre = '';
    let Texte = '';

    if (Classe == 'sm')
      {
      Titre = Reponse.Gagnant+"\n"+Reponse.Cadeau;
      Texte = TexteCourt(Reponse.Gagnant,6)+'<br/>'+TexteCourt(Reponse.Cadeau,6);
      }
    else if (Classe == 'lg')
      {
      Titre = 'Gagnant : '+Reponse.Gagnant+"\nCadeau : "+Reponse.Cadeau;
      Texte = 'Gagnant :<br/>'+TexteCourt(Reponse.Gagnant,10)+'<br/>Cadeau :<br/>'+TexteCourt(Reponse.Cadeau,10);
      }
    else
      {
      Titre = 'Le gagnant : '+Reponse.Gagnant+"\nLe cadeau : "+Reponse.Cadeau;
      Texte = 'Le gagnant :<br/>'+TexteCourt(Reponse.Gagnant,22)+'<br/>Le cadeau :<br/>'+TexteCourt(Reponse.Cadeau,22);
      }

    if (Reponse.Illustration) //0, false, undefined, null et "" sont considérés comme faux
      {
      Widget.attr('style','background-image:url('+Reponse.Illustration+');background-size:cover;');
      }
    Widget.attr('title',Titre);
    Widget.html('<span class="text-break '+Couleur+'">'+Texte+'</span>');
    }

  let Echec = function(Erreur)
    {
    Widget.html('<i class="fa-solid fa-exclamation-triangle '+Couleur+' ErreurNoel-'+Classe+'"></i>');
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
  const Jours = $('[data-style]');

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