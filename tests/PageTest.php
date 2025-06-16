<?php

namespace App\Tests;

use App\Repository\PageRepository;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    public function testGetMarkdownFile(): void
    {
        $pageRepository = new PageRepository();

        $this->assertEquals('tests/Fixtures/markdown-page.md', $pageRepository->getFile('tests/Fixtures/markdown-page'));
    }
}
