<?php

namespace App\Tests\Service;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tomrummet\Marker\Model\MarkerTypeEnum;
use Tomrummet\Marker\Repository\ScaffoldRepository;

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
