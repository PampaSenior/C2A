function ClicLoupe()
  {
  const Widget = $(event.currentTarget);
  const Numero = Widget.parent().parent().attr('id');
  const Gagnant = Widget.data('gagnant');
  const Cadeau = Widget.data('cadeau');
  const Photo = Widget.data('photo'); //Sera undefined si l'attribut n'existe pas
  const Clef = 'ZoomResultat';

  $('#'+Clef).find('#jour').html(Numero);
  $('#'+Clef).find('#gagnant').html(Gagnant);
  $('#'+Clef).find('#cadeau').html(Cadeau);
  if (Photo) //0, false, undefined, null et "" sont considérés comme faux
    {
    $('#'+Clef).find('#photo').removeClass('d-none');
    $('#'+Clef).find('#photo').attr('src',Photo);
    }
  else
    {
    $('#'+Clef).find('#photo').addClass('d-none'); //"addClass" vérifie la présence pour éviter d'ajouter un doublon
    $('#'+Clef).find('#photo').attr('src','');
    }
  $('#'+Clef).show();
  }