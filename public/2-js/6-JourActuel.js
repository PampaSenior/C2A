function CadeauDuJour(Clef)
  {
  localStorage.setItem('JourClic',Clef.data.Clef); //On sauvegarde comme clef unique l'année et le jour en cookie
  //localStorage.removeItem('JourClic'); //Pour supprimer le cookie js
  }

function JourActuel(J,M)
  {
  const LaDate = new Date();
  const Mois = LaDate.getMonth(); //Va de 0 à 11
  const Annee = LaDate.getFullYear(); //Année complète et pas sur 2 éléments
  const Jours = $('[data-style]');

  //Pour contraindre Id de 1 à nb de jours
  const JourMax = Math.min(J, Jours.length);

  const Clef = Annee+'-'+J;

  if (J <= JourMax && Mois == M-1) //Si on est le bon mois mais au maximum le 24/25
    {
    if (localStorage.getItem('JourClic') == null || localStorage.getItem('JourClic') != Clef)
      {
      $(Jours[J-1]).on('click',{'Clef':Clef},CadeauDuJour); //Mise en place du clic de sauvegarde de la révélation
      $(Jours[J-1]).addClass('AnimationOuvrable');
      }
    else
      {
      Jours[J-1].click(); //Utilisation de la révélation
      }
    }
  }