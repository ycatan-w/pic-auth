<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Stego\Lsb\Selector;

use Crayon\PicAuth\Stego\Lsb\Selector\RgbChannelSelector;
use PHPUnit\Framework\TestCase;

class RgbChannelSelectorTest extends TestCase
{
    public function testGetChannels(): void
    {
        $this->assertEquals(['red', 'green', 'blue'], (new RgbChannelSelector())->getChannels());
    }

    public function testValidate(): void
    {
        $img = imagecreate(110, 20);
        (new RgbChannelSelector())->validate($img, 'test');
        $this->assertTrue(true);
    }

    public function testValidateFailure(): void
    {
        $this->expectException(\Exception::class);
        $img = imagecreate(5, 5);
        (new RgbChannelSelector())->validate($img, str_repeat('1', 5 * 5 * 3 + 1));
    }
}
