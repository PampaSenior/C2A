function JourActuel(J,M)
  {
  const LaDate = new Date();
  const Jour = LaDate.getDate(); //Va de 1 à 31
  const Mois = LaDate.getMonth(); //Va de 0 à 11
  const Annee = LaDate.getFullYear(); //Année complète et pas sur 2 éléments
  const Jours = $('[data-style]');

  //Pour contraindre Id de 1 à nb de jours
  const JourMax = Math.min(J, Jours.length);

  //Pour le cookie js
  const Clef = 'JourClic';
  const Valeur = Jour+'-'+Mois+'-'+Annee;;

  if (J <= JourMax && Mois == M-1) //Si on est le bon mois mais au maximum le 24/25
    {
    if (localStorage.getItem(Clef) == null || localStorage.getItem(Clef) != Valeur)
      {
      $(Jours[J-1]).on('click',function() {localStorage.setItem(Clef,Valeur);}); //Mise en place du clic de sauvegarde de la révélation
      $(Jours[J-1]).addClass('AnimationOuvrable');
      }
    else
      {
      Jours[J-1].click(); //Utilisation de la révélation
      }
    }
  }