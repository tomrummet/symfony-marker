<?php

namespace Tomrummet\MarkerBundle\Tests\Service;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Tomrummet\MarkerBundle\Repository\PageRepository;

class PageTest extends KernelTestCase
{
    #[Test]
    public function pageContentDirectory(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $pageRepository = $container->get(PageRepository::class);
        $filesystem = new Filesystem();

        $this->assertTrue($filesystem->exists($this->getFixturesDirectory()));
        $this->assertEquals(
            $this->getFixturesDirectory(),
            $pageRepository->getContentDirectory(),
        );
    }

    #[Test]
    public function getFile(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $pageRepository = $container->get(PageRepository::class);
        $file = $pageRepository->getFile('markdown-page');

        $this->assertNotFalse($file);
        $this->assertEquals(
            $this->getFixturesDirectory() . 'markdown-page/Markdown page.md',
            $file->getPathName(),
        );
    }

    #[Test]
    public function getMarkDownFileContent (): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $pageRepository = $container->get(PageRepository::class);

        $file = $pageRepository->getFile(
            name: 'markdown-page',
        );

        $markdownContent = $pageRepository->getMarkdownContent($file);
        $expectedContent = <<<HTML
        <h1>Hello world!</h1>
        <p>This is a markdown file.</p>

        HTML;

        $this->assertEquals($expectedContent, $markdownContent);
    }

    #[Test]
    public function getPages(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $pageRepository = $container->get(PageRepository::class);

        $this->assertNotContains('index', $pageRepository->getPages());
        $this->assertEquals(
            [
                [
                    'path' => 'posts',
                    'title' => 'Posts',
                ],
                [
                    'path' => 'page/markdown-page',
                    'file' => 'Markdown page.md',
                    'title' => 'Markdown page',
                ],
            ],
            $pageRepository->getPages(),
        );
    }

    private function getFixturesDirectory(): string
    {
        return realpath(dirname(__FILE__) . '/../Fixtures/pages/') . '/';
    }
}
