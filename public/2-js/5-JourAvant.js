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
    if (Style == true)
      {
      Widget.addClass('BlocNoel-'+Classe);
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

$(function() {
  const LaDate = new Date();
  const Jour = LaDate.getDate(); //Va de 1 à 31
  const Mois = LaDate.getMonth(); //Va de 0 à 11 donc décembre = 11
  const Annee = LaDate.getFullYear(); //Année complète et pas sur 2 éléments
  const Jours = $('[data-style]');

  //Pour contraindre Id de 1 à nb de jours
  const JourMax = Math.min(Jour, Jours.length);

  if (Jour <= JourMax && Mois == 11) //Si on est en décembre mais au maximum le 25
    {
    for (var i=0; i<JourMax-1; i++) //Avant aujourd'hui on affiche les résultats en simulant des clics
      {
      $(Jours[i]).on('click',Clic);
      Jours[i].click();
      }
    $(Jours[Jour-1]).on('click',Clic); //Aujourd'hui on met un event clic
    }
});