<?php

namespace App\Repository;

use SplFileInfo;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PageRepository extends MarkerRepository
{
    public function __construct(
        public ParameterBagInterface $params,
    ) {}

    public function getContentDirectory(): string
    {
        return "{$this->params->get('kernel.project_dir')}/{$this->params->get('marker.directory.pages')}";
    }

    public function getFile(string $name): SplFileInfo|false
    {
        $pagePath = "{$this->getContentDirectory()}{$name}/";

        $filesystem = new Filesystem();
        if (!$filesystem->exists($pagePath)) {
            return false;
        }

        $finder = new Finder();
        $finder
            ->files()
            ->in($pagePath)
            ->name('*.md')
        ;

        if($finder->count() === 0) {
            return false;
        }

        $iterator = $finder->getIterator();
        $iterator->rewind();

        return $iterator->current();
    }

    public function getPages(): array
    {
        $defaultPages = [
            [
                'path' => 'posts',
                'title' => 'Posts',
            ],
        ];

        $files = $this->getFileList();

        $result = [];
        foreach ($files as $file) {
            $path = explode('/', $file->getPath());
            $name = end($path);

            $result[] = [
                'path' => "page/{$name}",
                'file' => $file->getFileName(),
                'title' => rtrim($file->getFilename(), '.md'),
            ];
        }

        return [...$defaultPages, ...$result];
    }

    public function getFileList(): Finder
    {
        $finder = new Finder();

        return $finder
            ->files()
            ->in($this->getContentDirectory() . '*')
            ->notName('Index.md')
            ->name('*.md')
        ;
    }
}
