<?php

namespace App\Tests\Service;

use DateTime;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Tomrummet\Marker\Model\MarkerTypeEnum;
use Tomrummet\Marker\Repository\ScaffoldRepository;

class ScaffoldTest extends KernelTestCase
{
    private string $fixturesFolder = 'tests/Fixtures/';

    #[Test]
    public function getType(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $scaffoldRepository = $container->get(ScaffoldRepository::class);

        $this->assertEquals('pages', $scaffoldRepository->getType(MarkerTypeEnum::PAGE));
        $this->assertEquals('posts', $scaffoldRepository->getType(MarkerTypeEnum::POST));
    }

    #[Test]
    public function getSlugByName(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $scaffoldRepository = $container->get(ScaffoldRepository::class);

        $this->assertEquals('test-post-aeoa', $scaffoldRepository->getSlugByName('Test post ÆØÅ'));
    }

    #[Test]
    public function createFolder(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $scaffoldRepository = $container->get(ScaffoldRepository::class);

        $pageName = 'Scaffold test page';
        $pageFolder = $scaffoldRepository->getPath(
            name: $pageName,
            type: MarkerTypeEnum::PAGE,
        );

        $this->assertEquals(
            $this->getTestContentsDirectory() . 'pages/scaffold-test-page',
            $pageFolder
        );

        $this->assertDirectoryDoesNotExist($pageFolder);
        $this->assertTrue($scaffoldRepository->createFolder(
            name: $pageName,
            type: MarkerTypeEnum::PAGE,
        ));
        $this->assertDirectoryExists($pageFolder);

        $postName = 'Scaffold test post';
        $postFolder = $scaffoldRepository->getPath(
            name: $postName,
            type: MarkerTypeEnum::POST,
        );

        $this->assertEquals(
            $this->getTestContentsDirectory() . 'posts/scaffold-test-post',
            $postFolder,
        );

        $this->assertDirectoryDoesNotExist($postFolder);
        $this->assertTrue($scaffoldRepository->createFolder(
            name: $postName,
            type: MarkerTypeEnum::POST,
        ));
        $this->assertDirectoryExists($postFolder);

        $filesystem = new Filesystem();
        $filesystem->remove($pageFolder);
        $filesystem->remove($postFolder);

        $this->assertDirectoryDoesNotExist($pageFolder);
        $this->assertDirectoryDoesNotExist($postFolder);
    }

    #[Test]
    public function createFiles(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $scaffoldRepository = $container->get(ScaffoldRepository::class);

        $pageName = 'Scaffold test page';
        $this->assertTrue($scaffoldRepository->createFolder(
            name: $pageName,
            type: MarkerTypeEnum::PAGE,
        ));

        $this->assertTrue($scaffoldRepository->createFiles(
            name: $pageName,
            type: MarkerTypeEnum::PAGE,
        ));

        $pageFolder = $scaffoldRepository->getPath(
            name: $pageName,
            type: MarkerTypeEnum::PAGE,
        );
        $this->assertFileExists("{$pageFolder}/{$pageName}.md");

        $postName = 'Scaffold test post';
        $this->assertTrue($scaffoldRepository->createFolder(
            name: $postName,
            type: MarkerTypeEnum::POST,
        ));

        $this->assertTrue($scaffoldRepository->createFiles(
            name: $postName,
            type: MarkerTypeEnum::POST,
        ));

        $postFolder = $scaffoldRepository->getPath(
            name: $postName,
            type: MarkerTypeEnum::POST,
        );
        $this->assertFileExists("{$postFolder}/content.md");
        $this->assertFileExists("{$postFolder}/metadata.yaml");

        $filesystem = new Filesystem();
        $filesystem->remove($pageFolder);
        $filesystem->remove($postFolder);

        $this->assertDirectoryDoesNotExist($pageFolder);
        $this->assertDirectoryDoesNotExist($postFolder);
    }

    #[Test]
    public function writeMetadataContent(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $scaffoldRepository = $container->get(ScaffoldRepository::class);

        $postName = 'Scaffold test post';
        $this->assertTrue($scaffoldRepository->createFolder(
            name: $postName,
            type: MarkerTypeEnum::POST,
        ));

        $this->assertTrue($scaffoldRepository->createFiles(
            name: $postName,
            type: MarkerTypeEnum::POST,
        ));

        $postFolder = $scaffoldRepository->getPath(
            name: $postName,
            type: MarkerTypeEnum::POST,
        );

        $content = [
            'title' => $postName,
            'author' => 'Test Author',
            'published' => (new DateTime())->format('Y-m-d H:i:s'),
            'tags' => [
                'phpunit',
                'testing',
                'cool',
                'stuff',
            ],
        ];

        $this->assertEquals($content, $scaffoldRepository->writeMetadataContent($content));

        $yamlContent = Yaml::dump($content);
        
        $filesystem = new Filesystem();
        $fileContent = $filesystem->readFile("{$postFolder}/metadata.yaml");

        $this->assertEquals($yamlContent, $fileContent);

        $filesystem = new Filesystem();
        $filesystem->remove($postFolder);
    }

    private function getTestContentsDirectory(): string
    {
        self::bootKernel();
        $container = static::getContainer();

        return $container->getParameter('kernel.project_dir') . '/' . $this->fixturesFolder;
    }
}
