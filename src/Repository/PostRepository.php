<?php

namespace App\Repository;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class PostRepository extends MarkerRepository
{
    public function __construct(
        public ParameterBagInterface $params,
    ) {}

    public function getContentDirectory(): string
    {
        return "{$this->params->get('kernel.project_dir')}/{$this->params->get('marker.directory.posts')}";
    }

    public function getFile(string $name): string|false
    {
        $filename = "{$this->getContentDirectory()}{$name}/content.md";

        $filesystem = new Filesystem();
        if (!$filesystem->exists($filename)) {
            return false;
        }

        return $filename;
    }

    public function getMetaData(string $name): array|false
    {
        $filename = "{$this->getContentDirectory()}{$name}/metadata.yaml";

        $filesystem = new Filesystem();
        if (!$filesystem->exists($filename)) {
            return false;
        }

        return Yaml::parse($filesystem->readFile($filename));
    }

    public function getPost(string $name): array
    {
        return [
            'metadata' => $this->getMetaData($name),
            'content' => $this->getMarkdownContent($this->getFile($name)),
        ];
    }

    public function getPosts(
        ?int $limit = null,
    ): array {
        $posts = $this->getPostsList();

        $result = [];
        foreach ($posts as $post) {
            $path = explode('/', $post->getPath());
            $name = end($path);
            $result[] = ['slug' => $name, ...$this->getMetaData($name)];
        }

        usort($result, function($a, $b) {
            return $b['published'] <=> $a['published'];
        });

        return array_slice($result, 0, $limit);
    }

    public function getPostsList(): Finder {
        $finder = new Finder();

        return $finder
            ->files()
            ->in($this->getContentDirectory() . '*')
            ->name('metadata.yaml')
        ;
    }
}
