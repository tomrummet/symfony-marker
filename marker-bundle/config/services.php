<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
            ->public()

        ->load('Tomrummet\\MarkerBundle\\Repository\\', '../src/Repository/')
        ->load('Tomrummet\\MarkerBundle\\Command\\', '../src/Command/')
    ;

    $container->parameters()
        ->set('marker.directory.pages', env('MARKER_DIRS_PAGES'))
        ->set('marker.directory.posts', env('MARKER_DIRS_POSTS'))
    ;
};