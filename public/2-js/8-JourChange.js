function JourChange(JourOriginal)
  {
  const LaDate = new Date();
  const Jour = LaDate.getDate(); //Va de 1 à 31

  if (JourOriginal != Jour && typeof Rafraichir === 'undefined') //Si on est plus le même jour et que le rechargement n'est pas en cours
    {
    Rafraichir = true; //Permet d'empêcher la condition supra en définissant la variable
    location.reload(true);
    }
  }