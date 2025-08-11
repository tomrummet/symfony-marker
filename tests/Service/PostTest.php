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
        $filesystem = new Filesystem();

        $this->assertTrue($filesystem->exists($this->fixturesFolder), "Couldn't find folder {$this->fixturesFolder}");

        self::bootKernel();
        $container = static::getContainer();
        $postRepository = $container->get(PostRepository::class);

        $this->assertEquals(
            $this->getTestContentsDirectory(),
            $postRepository->getContentDirectory(),
        );
    }

    public function testGetFile(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $postRepository = $container->get(PostRepository::class);

        $file = $postRepository->getFile('not-a-test-post');
        $this->assertFalse($file);

        $file = $postRepository->getFile('test-post');
        $this->assertNotFalse($file);
        $this->assertEquals(
            $this->getTestContentsDirectory() . 'test-post/content.md',
            $file,
        );
    }

    public function testGetMarkDownFileContent (): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $postRepository = $container->get(PostRepository::class);

        $file = $postRepository->getFile(
            name: 'test-post',
        );

        $markdownContent = $postRepository->getMarkdownContent($file);
        $expectedContent = <<<HTML
        <h1>Test post</h1>
        <p>This is the content of the test post.</p>

        HTML;

        $this->assertEquals($expectedContent, $markdownContent);
    }

    public function testGetMetaData(): void
    {
        $filesystem = new Filesystem();

        $this->assertFalse($filesystem->exists($this->getTestContentsDirectory() . 'not-a-test-post/metadata.yaml'));
        $this->assertTrue($filesystem->exists($this->getTestContentsDirectory() . 'test-post/metadata.yaml'));
        $this->assertTrue($filesystem->exists($this->getTestContentsDirectory() . 'another-test-post/metadata.yaml'));

        self::bootKernel();
        $container = static::getContainer();
        $postRepository = $container->get(PostRepository::class);

        $metadata = $postRepository->getMetaData('not-a-test-post');
        $this->assertFalse($metadata);

        $metadata = $postRepository->getMetaData('test-post');
        $this->assertNotFalse($metadata);
        $this->assertIsArray($metadata);
        $this->assertArrayHasKey('post', $metadata);
        $this->assertArrayHasKey('title', $metadata['post']);
        $this->assertEquals('Test post', $metadata['post']['title']);
        $this->assertArrayHasKey('published', $metadata['post']);
        $this->assertEquals('2025-08-11 21:01:59', $metadata['post']['published']);
    }

    public function testGetPosts(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $postRepository = $container->get(PostRepository::class);

        $posts = $postRepository->getPosts();
        $this->assertIsArray($posts);
        $this->assertNotEmpty($posts);
        $this->assertEquals(2, count($posts));
        $this->assertArrayNotHasKey('not-a-test-post', $posts);
        $this->assertArrayHasKey('test-post', $posts);
        $this->assertArrayHasKey('another-test-post', $posts);

        $posts = $postRepository->getPosts(
            limit: 1,
        );
        $this->assertEquals(1, count($posts), "Limit amount of posts isn't respected");
    }

    private function getTestContentsDirectory(): string
    {
        self::bootKernel();
        $container = static::getContainer();

        return $container->getParameter('kernel.project_dir') . '/' . $this->fixturesFolder;
    }
}
