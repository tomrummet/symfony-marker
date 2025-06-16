<?php

namespace App\Repository;

class PageRepository
{
    public function getFile(string $name): string|false
    {
        $markdownFilename = "{$name}.md";
        if (!file_exists($markdownFilename)) {
            return false;
        }

        return $markdownFilename;
    }
}
