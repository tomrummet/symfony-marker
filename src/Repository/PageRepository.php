<?php

namespace App\Repository;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PageRepository extends MarkdownRepository
{
    public function __construct(
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

    public function getPages(): array
    {
        $files = $this->getFileList();

        $result = [];
        foreach ($files as $file) {
            $result[] = rtrim($file->getFilename(), '.md');
        }

        return $result;
    }

    public function getFileList(): Finder
    {
        $finder = new Finder();

        return $finder
            ->files()
            ->in($this->getContentDirectory())
            ->notName('index.md')
        ;
    }
}
