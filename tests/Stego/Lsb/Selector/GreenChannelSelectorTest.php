<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Stego\Lsb\Selector;

use Crayon\PicAuth\Stego\Lsb\Selector\GreenChannelSelector;
use PHPUnit\Framework\TestCase;

class GreenChannelSelectorTest extends TestCase
{
    public function testGetChannels(): void
    {
        $this->assertEquals(['green'], (new GreenChannelSelector())->getChannels());
    }

    public function testValidate(): void
    {
        $img = imagecreate(110, 20);
        (new GreenChannelSelector())->validate($img, 'test');
        $this->assertTrue(true);
    }

    public function testValidateFailure(): void
    {
        $this->expectException(\Exception::class);
        $img = imagecreate(5, 5);
        (new GreenChannelSelector())->validate($img, str_repeat('1', 5 * 5 + 1));
    }
}
