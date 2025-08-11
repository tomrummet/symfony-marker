<?php

namespace App\Repository;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\IOException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class MarkdownRepository
{
    public function __construct(
        public ParameterBagInterface $params
    ) {}

    public function getMarkdownContent(string $file): string
    {
        try {
            $filesystem = new Filesystem();
            $rawFileContent = $filesystem->readFile($file);

            return (new CommonMarkConverter())
                ->convert($rawFileContent)
                ->getContent()
            ;
        } catch(IOException $e) {
            return '# Ups!';
        }
    }

    public function getContentDirectory(): string
    {
        return $this->params->get('marker.directory.pages');
    }
}
