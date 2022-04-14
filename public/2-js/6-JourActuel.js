function CadeauDuJour(Clef)
  {
  localStorage.setItem('JourClic',Clef.data.Clef); //On sauvegarde comme clef unique l'année et le jour en cookie
  //localStorage.removeItem('JourClic'); //Pour supprimer le cookie js
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
    if (localStorage.getItem('JourClic') == null || localStorage.getItem('JourClic') != Annee+'-'+Jour)
      {
      $(Jours[Jour-1]).on('click',{'Clef':Annee+'-'+Jour},CadeauDuJour);
      $(Jours[Jour-1]).addClass('AnimationOuvrable');
      }
    else
      {
      Jours[Jour-1].click();
      }
    }
});