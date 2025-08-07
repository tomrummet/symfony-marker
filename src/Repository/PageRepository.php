<?php

namespace App\Repository;

use League\CommonMark\CommonMarkConverter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class PageRepository
{
    public function __construct(
        public MarkdownRepository $markdownRepository,
        public ParameterBagInterface $params,
    ) {}

    public function getContentDirectory(): string
    {
        return "{$this->params->get('kernel.project_dir')}/{$this->params->get('marker.directory.pages')}";
    }

    public function getFile(string $name): string|false
    {
        $filename = "{$this->getContentDirectory()}{$name}.md";

        $filesystem = new Filesystem();
        if (!$filesystem->exists($filename)) {
            return false;
        }

        return $filename;
    }

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
}
