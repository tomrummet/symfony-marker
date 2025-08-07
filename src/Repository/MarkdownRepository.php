<?php

namespace App\Repository;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MarkdownRepository
{
    public function __construct(
        public ParameterBagInterface $params
    ) {}

    public function getContentDirectory(): string
    {
        return $this->params->get('marker.directory.pages');
    }
}
