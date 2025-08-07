<?php

namespace App\Tests\Service;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class PageTest extends KernelTestCase
{
    private string $fixturesFolder = 'tests/Fixtures/pages/';

    public function testGetPageContentDirectory(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $pageRepository = $container->get(PageRepository::class);

        $filesystem = new Filesystem();

        $this->assertTrue($filesystem->exists($this->fixturesFolder));
        $this->assertEquals(
            $container->getParameter('kernel.project_dir') . '/' . $this->fixturesFolder,
            $pageRepository->getContentDirectory(),
        );
    }

    public function testGetFile(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $pageRepository = $container->get(PageRepository::class);
        $file = $pageRepository->getFile('markdown-page');

        $this->assertNotFalse($file);
        $this->assertEquals(
            $container->getParameter('kernel.project_dir') . '/' . $this->fixturesFolder . 'markdown-page.md',
            $file,
        );
    }

    public function testGetMarkDownFileContent (): void
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
}
