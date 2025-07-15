<?php

namespace App\Tests;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class PageTest extends KernelTestCase
{
    private string $fixturesFolder = 'tests/Fixtures/pages/';

    public function testGetMarkdownFile(): void
    {
        $pageRepository = new PageRepository();

        $this->assertEquals(
            'tests/Fixtures/pages/markdown-page.md',
            $pageRepository->getFile('tests/Fixtures/pages', 'markdown-page')
        );
    }

    public function testGetMarkDownFileContent (): void
    {
        $filesystem = new Filesystem();

        $this->assertTrue($filesystem->exists($this->fixturesFolder));

        $pageRepository = new PageRepository();
        $filePath = $pageRepository->getFile(
            name: 'markdown-page.md',
            directory: $this->fixturesFolder,
        );

        $markdownContent = $pageRepository->getMarkdownContent($filePath);
        $expectedContent = <<<HTML
        <h1>Hello world!</h1>
        <p>This is a markdown file.</p>

        HTML;

        $this->assertEquals($expectedContent, $markdownContent);
    }

}
