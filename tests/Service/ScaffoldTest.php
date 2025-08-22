<?php

namespace App\Tests\Service;

use App\Model\MarkerTypeEnum;
use App\Repository\ScaffoldRepository;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ScaffoldTest extends KernelTestCase
{
    #[Test]
    public function getType(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $ScaffoldRepository = $container->get(ScaffoldRepository::class);

        $this->assertEquals('pages', $ScaffoldRepository->getType(MarkerTypeEnum::PAGE));
        $this->assertEquals('posts', $ScaffoldRepository->getType(MarkerTypeEnum::POST));
    }
}
