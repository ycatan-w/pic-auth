<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Stego;

use Crayon\PicAuth\Stego\Lsb\LsbMode;
use Crayon\PicAuth\Stego\Lsb\LsbSteganography;
use Crayon\PicAuth\Stego\Lsb\Payload\PayloadCreatorInterface;
use Crayon\PicAuth\Stego\Lsb\Payload\PayloadInterface;
use Crayon\PicAuth\Stego\Lsb\Selector\ChannelSelectorFactory;
use Crayon\PicAuth\Stego\Lsb\Selector\ChannelSelectorInterface;
use PHPUnit\Framework\TestCase;

class LsbSteganographyTest extends TestCase
{
    public function testRandomModeFailure(): void
    {
        $this->expectException(\Exception::class);
        new LsbSteganography(
            mode: LsbMode::RANDOM
        );
    }

    public function testEmbed(): void
    {
        $bits        = '0000000000000000000000000111000000000100011111110100100001100101011011000110110001101111001000000101011101101111011100100110110001100100001000010010000100100001';
        $mockPayload = $this->getMockBuilder(PayloadInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPayload->expects($this->once())
            ->method('format')
            ->willReturn($bits);
        $mockPayloadCreator = $this->getMockBuilder(PayloadCreatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPayloadCreator->expects($this->once())
            ->method('create')
            ->willReturn($mockPayload);

        $mockChannel = $this->getMockBuilder(ChannelSelectorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockChannel->expects($this->exactly(strlen($bits)))
            ->method('getChannels')
            ->willReturn(['blue']);

        $mockChannelFactory = $this->getMockBuilder(ChannelSelectorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockChannelFactory->expects($this->once())
            ->method('create')
            ->with(LsbMode::BLUE, null)
            ->willReturn($mockChannel);

        $stegnanography = new LsbSteganography(
            mode: LsbMode::BLUE,
            payloadCreator: $mockPayloadCreator,
            channelSelectorFactory: $mockChannelFactory,
        );

        $b64Img = $stegnanography->embed(__DIR__ . '/image_not_encoded.png', 'Hello World!!!');
        $this->assertEquals(base64_encode(file_get_contents(__DIR__ . '/image_encoded.png')), $b64Img);
    }

    public function testEmbedRGB(): void
    {
        $bits        = '0000000000000000000000000111000000000100011111110100100001100101011011000110110001101111001000000101011101101111011100100110110001100100001000010010000100100001';
        $mockPayload = $this->getMockBuilder(PayloadInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPayload->expects($this->once())
            ->method('format')
            ->willReturn($bits);
        $mockPayloadCreator = $this->getMockBuilder(PayloadCreatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPayloadCreator->expects($this->once())
            ->method('create')
            ->willReturn($mockPayload);

        $mockChannel = $this->getMockBuilder(ChannelSelectorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockChannel->expects($this->exactly(intval(ceil(strlen($bits) / 3))))
            ->method('getChannels')
            ->willReturn(['red', 'green', 'blue']);

        $mockChannelFactory = $this->getMockBuilder(ChannelSelectorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockChannelFactory->expects($this->once())
            ->method('create')
            ->with(LsbMode::RGB, null)
            ->willReturn($mockChannel);

        $stegnanography = new LsbSteganography(
            mode: LsbMode::RGB,
            payloadCreator: $mockPayloadCreator,
            channelSelectorFactory: $mockChannelFactory,
        );

        $b64Img = $stegnanography->embed(__DIR__ . '/image_not_encoded.png', 'Hello World!!!');
        $this->assertEquals(base64_encode(file_get_contents(__DIR__ . '/image_encoded_rgb.png')), $b64Img);
    }

    public function testExtract(): void
    {
        $bits        = '0000000000000000000000000111000000000100011111110100100001100101011011000110110001101111001000000101011101101111011100100110110001100100001000010010000100100001';
        $mockPayload = $this->getMockBuilder(PayloadInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPayload->expects($this->once())
            ->method('parse')
            ->with($this->stringContains($bits))
            ->willReturn('Hello World!!!');
        $mockPayloadCreator = $this->getMockBuilder(PayloadCreatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPayloadCreator->expects($this->once())
            ->method('create')
            ->willReturn($mockPayload);

        $mockChannel = $this->getMockBuilder(ChannelSelectorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockChannel->expects($this->exactly(strlen($bits)))
            ->method('getChannels')
            ->willReturn(['blue']);

        $mockChannelFactory = $this->getMockBuilder(ChannelSelectorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockChannelFactory->expects($this->once())
            ->method('create')
            ->with(LsbMode::BLUE, null)
            ->willReturn($mockChannel);

        $stegnanography = new LsbSteganography(
            mode: LsbMode::BLUE,
            payloadCreator: $mockPayloadCreator,
            channelSelectorFactory: $mockChannelFactory,
        );

        $msg = $stegnanography->extract(__DIR__ . '/image_encoded.png');
        $this->assertEquals('Hello World!!!', $msg);
    }

    public function testLoadImageFailure(): void
    {
        $mockPayload = $this->getMockBuilder(PayloadInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPayloadCreator = $this->getMockBuilder(PayloadCreatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPayloadCreator->expects($this->once())
            ->method('create')
            ->willReturn($mockPayload);

        $mockChannel = $this->getMockBuilder(ChannelSelectorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockChannelFactory = $this->getMockBuilder(ChannelSelectorFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockChannelFactory->expects($this->once())
            ->method('create')
            ->with(LsbMode::BLUE, null)
            ->willReturn($mockChannel);
        $this->expectException(\Exception::class);
        $stegnanography = new LsbSteganography(
            mode: LsbMode::BLUE,
            payloadCreator: $mockPayloadCreator,
            channelSelectorFactory: $mockChannelFactory,
        );
        $stegnanography->extract(__DIR__ . '/image_not_encoded.svg');
    }
}
