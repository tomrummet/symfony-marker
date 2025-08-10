<?php

namespace App\Tests\Service;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class PostTest extends KernelTestCase
{
    protected string $fixturesFolder = 'tests/Fixtures/posts/';

    public function testGetPostContentDirectory(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $postRepository = $container->get(PostRepository::class);
        $filesystem = new Filesystem();

        $this->assertTrue($filesystem->exists($this->fixturesFolder));
        $this->assertEquals(
            $this->getTestContentsDirectory(),
            $postRepository->getContentDirectory(),
        );
    }

    private function getTestContentsDirectory(): string
    {
        self::bootKernel();
        $container = static::getContainer();

        return $container->getParameter('kernel.project_dir') . '/' . $this->fixturesFolder;
    }
}
