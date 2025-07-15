<?php

namespace App\Repository;

use League\CommonMark\CommonMarkConverter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class PageRepository
{
    private string $defaultDirectory = 'public/contents/pages/';

    public function __construct(
        public ParameterBagInterface $params,
    ) {
    }

    public function getFile(
        string $name,
        ?string $directory = null,
    ): string|false
    {
        $name = is_null($directory)
            ? $this->defaultDirectory . $name
            : $directory . $name
        ;

        $markdownFilename = "{$this->params->get('kernel.project_dir')}/{$name}.md";
        if (!file_exists($markdownFilename)) {
            return false;
        }

        return $markdownFilename;
    }

    public function getMarkdownContent(string $filePath): string
    {
        try {
            $filesystem = new Filesystem();
            $rawFileContent = $filesystem->readFile($filePath);

            return (new CommonMarkConverter())
                ->convert($rawFileContent)
                ->getContent()
            ;
        } catch(IOException $e) {
            return '# Ups!';
        }
    }
}
