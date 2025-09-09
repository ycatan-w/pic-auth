<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Stego\Lsb\Selector;

use Crayon\PicAuth\Stego\Lsb\Selector\BlueChannelSelector;
use PHPUnit\Framework\TestCase;

class BlueChannelSelectorTest extends TestCase
{
    public function testGetChannels(): void
    {
        $this->assertEquals(['blue'], (new BlueChannelSelector())->getChannels());
    }

    public function testValidate(): void
    {
        $img = imagecreate(110, 20);
        (new BlueChannelSelector())->validate($img, 'test');
        $this->assertTrue(true);
    }

    public function testValidateFailure(): void
    {
        $this->expectException(\Exception::class);
        $img = imagecreate(5, 5);
        (new BlueChannelSelector())->validate($img, str_repeat('1', 5 * 5 + 1));
    }
}
