<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Stego\Lsb\Selector;

use Crayon\PicAuth\Stego\Lsb\LsbMode;
use Crayon\PicAuth\Stego\Lsb\Selector\BlueChannelSelector;
use Crayon\PicAuth\Stego\Lsb\Selector\ChannelSelectorFactory;
use Crayon\PicAuth\Stego\Lsb\Selector\GreenChannelSelector;
use Crayon\PicAuth\Stego\Lsb\Selector\RandomChannelSelector;
use Crayon\PicAuth\Stego\Lsb\Selector\RedChannelSelector;
use Crayon\PicAuth\Stego\Lsb\Selector\RgbChannelSelector;
use PHPUnit\Framework\TestCase;

class ChannelSelectorFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $factory = new ChannelSelectorFactory();

        $this->assertInstanceOf(RedChannelSelector::class, $factory->create(LsbMode::RED));
        $this->assertInstanceOf(GreenChannelSelector::class, $factory->create(LsbMode::GREEN));
        $this->assertInstanceOf(BlueChannelSelector::class, $factory->create(LsbMode::BLUE));
        $this->assertInstanceOf(RgbChannelSelector::class, $factory->create(LsbMode::RGB));
        $this->assertInstanceOf(RandomChannelSelector::class, $factory->create(LsbMode::RANDOM, 1337));
    }

    public function testCreateRandomFailure(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new ChannelSelectorFactory())->create(LsbMode::RANDOM);
    }
}
