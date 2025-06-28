<?php

namespace App\Tests;

use App\Repository\PageRepository;
use League\CommonMark\CommonMarkConverter;
use League\Flysystem\Filesystem as FlysystemFilesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class PageTest extends KernelTestCase
{
    private string $fixturesFolder = 'tests/Fixtures/pages/';

    public function testGetMarkdownFile(): void
    {
        $pageRepository = new PageRepository();

        $this->assertEquals('tests/Fixtures/pages/markdown-page.md', $pageRepository->getFile('tests/Fixtures/pages/markdown-page'));
    }

    public function testGetMarkDownFileContent (): void
    {
        $filesystem = new Filesystem();

        $this->assertTrue($filesystem->exists($this->fixturesFolder));

        $rawFileContent = $filesystem->readFile($this->fixturesFolder . 'markdown-page.md');
        $markdownContent = (new CommonMarkConverter())
            ->convert($rawFileContent)
            ->getContent()
        ;

        $expectedContent = <<<HTML
        <h1>Hello world!</h1>
        <p>This is a markdown file.</p>

        HTML;

        $this->assertEquals($expectedContent, $markdownContent);
    }

}
