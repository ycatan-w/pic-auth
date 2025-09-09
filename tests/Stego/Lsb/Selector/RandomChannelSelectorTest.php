<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Stego\Lsb\Selector;

use Crayon\PicAuth\Stego\Lsb\Selector\RandomChannelSelector;
use PHPUnit\Framework\TestCase;

class RandomChannelSelectorTest extends TestCase
{
    public function testGetChannels(): void
    {
        $selector = new RandomChannelSelector(1337);
        $this->assertEquals(['green'], $selector->getChannels());
        $this->assertEquals(['green'], $selector->getChannels());
        $this->assertEquals(['blue'], $selector->getChannels());
        $selector = new RandomChannelSelector(1337);
        $this->assertEquals(['green'], $selector->getChannels());
        $this->assertEquals(['green'], $selector->getChannels());
        $this->assertEquals(['blue'], $selector->getChannels());
    }

    public function testValidate(): void
    {
        $img = imagecreate(110, 20);
        (new RandomChannelSelector(1337))->validate($img, 'test');
        $this->assertTrue(true);
    }

    public function testValidateFailure(): void
    {
        $this->expectException(\Exception::class);
        $img = imagecreate(5, 5);
        (new RandomChannelSelector(1337))->validate($img, str_repeat('1', 5 * 5 + 1));
    }
}
