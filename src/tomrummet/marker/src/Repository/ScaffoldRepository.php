<?php

namespace Tomrummet\Marker\Repository;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;
use Tomrummet\Marker\Model\MarkerTypeEnum;

class ScaffoldRepository
{
    public function __construct(
        public MarkerRepository $markerRepository,
        public SluggerInterface $slugger,
    ) {}

    public function getType(MarkerTypeEnum $type): string
    {
        return $type->folder();
    }

    public function getSlugByName(string $name): string
    {
        return strtolower($this->slugger->slug($name));
    }

    public function getPath(
        string $name,
        MarkerTypeEnum $type,
    ): string {
        $slug = $this->getSlugByName($name);
        $path = $this->markerRepository->getContentDirectory($type->folder());

        return $path . $slug;
    }

    public function createFolder(
        string $name,
        MarkerTypeEnum $type,
    ): bool {
        $path = $this->getPath(
            name: $name,
            type: $type,
        );

        try {
            $filesystem = new Filesystem();
            $filesystem->mkdir($path);

            return true;
        } catch (IOException $e) {
            return false;
        }
    }

    public function createFiles(
        string $name,
        MarkerTypeEnum $type,
    ): bool {
        $path = $this->getPath(
            name: $name,
            type: $type,
        );

        $files['content'] = $type === MarkerTypeEnum::PAGE
            ? "{$name}.md"
            : "content.md"
        ;

        if ($type === MarkerTypeEnum::POST) {
            $files['metadata'] = 'metadata.yaml';
        }

        try {
            $filesystem = new Filesystem();

            array_walk($files, static fn ($file) => $filesystem->touch("{$path}/{$file}"));

            return true;
        } catch (IOException $e) {
            return false;
        }
    }
}
