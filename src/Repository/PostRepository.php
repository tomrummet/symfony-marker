<?php

namespace App\Repository;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PostRepository
{
    public function __construct(
        public MarkdownRepository $markdownRepository,
        public ParameterBagInterface $params,
    ) {}

    public function getContentDirectory(): string
    {
        return "{$this->params->get('kernel.project_dir')}/{$this->params->get('marker.directory.posts')}";
    }
}
