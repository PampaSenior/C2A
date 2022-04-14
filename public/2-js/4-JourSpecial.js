function JourSpecial(J, M)
  {
  const Nom = 'JourSpecial';
  const LaDate = new Date();
  const Jour = LaDate.getDate(); //Va de 1 à 31
  const Mois = LaDate.getMonth(); //Va de 0 à 11 donc décembre = 11
  const Annee = LaDate.getFullYear(); //Année complète et pas sur 2 éléments

  if (Jour == J && Mois == M)
    {
    if (localStorage.getItem(Nom) == null || localStorage.getItem(Nom) != Annee)
      {
      $('#'+Nom).show();
      localStorage.setItem(Nom,Annee); //On sauvegarde l'année en cookie
      //localStorage.removeItem(Nom); //Pour supprimer le cookie js
      }
    }
  }