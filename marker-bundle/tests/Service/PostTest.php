<?php

namespace Tomrummet\MarkerBundle\Tests\Service;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Tomrummet\MarkerBundle\Repository\PostRepository;

class PostTest extends KernelTestCase
{
    #[Test]
    public function getPostContentDirectory(): void
    {
        $filesystem = new Filesystem();

        $this->assertTrue($filesystem->exists($this->getFixturesDirectory()), "Couldn't find folder {$this->getFixturesDirectory()}");

        self::bootKernel();
        $container = static::getContainer();
        $postRepository = $container->get(PostRepository::class);

        $this->assertEquals(
            $this->getFixturesDirectory(),
            $postRepository->getContentDirectory(),
        );
    }

    #[Test]
    public function getFile(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $postRepository = $container->get(PostRepository::class);

        $file = $postRepository->getFile('not-a-test-post');
        $this->assertFalse($file);

        $file = $postRepository->getFile('test-post');
        $this->assertNotFalse($file);
        $this->assertEquals(
            $this->getFixturesDirectory() . 'test-post/content.md',
            $file,
        );
    }

    #[Test]
    public function getMarkDownFileContent (): void
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

    #[Test]
    public function getMetaData(): void
    {
        $filesystem = new Filesystem();

        $this->assertFalse($filesystem->exists($this->getFixturesDirectory() . 'not-a-test-post/metadata.yaml'));
        $this->assertTrue($filesystem->exists($this->getFixturesDirectory() . 'test-post/metadata.yaml'));
        $this->assertTrue($filesystem->exists($this->getFixturesDirectory() . 'another-test-post/metadata.yaml'));

        self::bootKernel();
        $container = static::getContainer();
        $postRepository = $container->get(PostRepository::class);

        $metadata = $postRepository->getMetaData('not-a-test-post');
        $this->assertFalse($metadata);

        $metadata = $postRepository->getMetaData('test-post');
        $this->assertNotFalse($metadata);
        $this->assertIsArray($metadata);
        $this->assertArrayHasKey('title', $metadata);
        $this->assertEquals('Test post', $metadata['title']);
        $this->assertArrayHasKey('summary', $metadata);
        $this->assertEquals('This is a summary', $metadata['summary']);
        $this->assertArrayHasKey('published', $metadata);
        $this->assertEquals('2025-08-11 21:01:59', $metadata['published']);
    }

    #[Test]
    public function getPosts(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $postRepository = $container->get(PostRepository::class);

        $posts = $postRepository->getPosts();
        $this->assertIsArray($posts);
        $this->assertNotEmpty($posts);
        $this->assertEquals(2, count($posts));

        $this->assertEmpty(array_filter($posts, static fn ($post) => $post['slug'] === 'not-a-test-post'));
        $this->assertNotEmpty(array_filter($posts, static fn ($post) => $post['slug'] === 'test-post'));
        $this->assertNotEmpty(array_filter($posts, static fn ($post) => $post['slug'] === 'another-test-post'));

        $this->assertEquals('another-test-post', $posts[0]['slug'], 'The first post isn\'t the first published');
        $this->assertEquals('test-post', $posts[1]['slug'], 'The second post isn\'t the second published');

        $posts = $postRepository->getPosts(
            limit: 1,
        );
        $this->assertEquals(1, count($posts), "Limit amount of posts isn't respected");
    }

    private function getFixturesDirectory(): string
    {
        return realpath(dirname(__FILE__) . '/../Fixtures/posts/') . '/';
    }
}
