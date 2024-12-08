<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Entity\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $container): void {
    $application = new Application();

    foreach ($application->getParametres() as $clef => $parametre) {
        /* Exemple à produire : $container->setParameter('Mot2Passe','%env(string:MOT_2_PASSE)%'); */
        $container->setParameter($clef, "%env({$parametre['type']}:{$parametre['nom']})%");
    }
};
