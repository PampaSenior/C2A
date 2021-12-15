$(document).ready(function() {
  const LaDate = new Date();
  const Jour = LaDate.getDate(); //Va de 1 à 31
  const Mois = LaDate.getMonth(); //Va de 0 à 11 donc décembre = 11
  const Annee = LaDate.getFullYear(); //Année complète et pas sur 2 éléments

  if (Jour == 25 && Mois == 11) //Si on est le 25 décembre
    {
    if (localStorage.getItem('BonneFete') == null || localStorage.getItem('BonneFete') != Annee)
      {
      $('#ModaleBonneFete').show();
      localStorage.setItem('BonneFete',Annee); //On sauvegarde l'année en cookie
      //localStorage.removeItem('BonneFete'); //Pour supprimer le cookie js
      }
    }
});