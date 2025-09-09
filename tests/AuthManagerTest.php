<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth;

use Crayon\PicAuth\AuthManager;
use Crayon\PicAuth\AuthStamp;
use Crayon\PicAuth\Config\AuthConfig;
use Crayon\PicAuth\Hasher\HasherInterface;
use Crayon\PicAuth\Stego\SteganographyInterface;
use Crayon\PicAuth\Token\TokenGeneratorInterface;
use PHPUnit\Framework\TestCase;

class AuthManagerTest extends TestCase
{
    public function testStamp(): void
    {
        $tokenGenerator = $this->getMockBuilder(TokenGeneratorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $tokenGenerator->expects($this->once())
            ->method('generate')
            ->with(32)
            ->willReturn('generated_token');

        $steganography = $this->getMockBuilder(SteganographyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $steganography->expects($this->once())
            ->method('embed')
            ->with('image_path', 'generated_token')
            ->willReturn('embeded_image');

        $hasher = $this->getMockBuilder(HasherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hasher->expects($this->once())
            ->method('hash')
            ->with('embeded_image')
            ->willReturn('hashed_image');

        $authManager = new AuthManager(
            new AuthConfig(
                tokenGenerator: $tokenGenerator,
                steganography: $steganography,
                hasher: $hasher
            )
        );
        $authStamp = $authManager->stamp('image_path');
        $this->assertEquals('generated_token', $authStamp->token);
        $this->assertEquals('hashed_image', $authStamp->hash);
        $this->assertEquals('embeded_image', $authStamp->stampedImage);
    }

    public function testVerifyStamp(): void
    {
        $steganography = $this->getMockBuilder(SteganographyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $steganography->expects($this->once())
            ->method('extract')
            ->with(__DIR__ . '/Stego/Lsb/image_encoded.png')
            ->willReturn('extracted_token');

        $hasher = $this->getMockBuilder(HasherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hasher->expects($this->once())
            ->method('verify')
            ->with($this->isString(), 'hash')
            ->willReturn(true);

        $authManager = new AuthManager(
            new AuthConfig(
                steganography: $steganography,
                hasher: $hasher
            )
        );

        $authStamp = new AuthStamp(
            token: 'extracted_token',
            hash: 'hash',
        );
        $this->assertTrue($authManager->verifyStamp(__DIR__ . '/Stego/Lsb/image_encoded.png', $authStamp));
    }

    public function testVerifyStampInvalidHashImage(): void
    {
        $hasher = $this->getMockBuilder(HasherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hasher->expects($this->once())
            ->method('verify')
            ->with($this->isString(), 'hash')
            ->willReturn(false);

        $authManager = new AuthManager(
            new AuthConfig(
                hasher: $hasher
            )
        );

        $authStamp = new AuthStamp(
            token: 'extracted_token',
            hash: 'hash',
        );
        $this->assertFalse($authManager->verifyStamp(__DIR__ . '/Stego/Lsb/image_encoded.png', $authStamp));
    }

    public function testVerifyStampInvalidToken(): void
    {
        $steganography = $this->getMockBuilder(SteganographyInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $steganography->expects($this->once())
            ->method('extract')
            ->with(__DIR__ . '/Stego/Lsb/image_encoded.png')
            ->willReturn('extracted_token');

        $hasher = $this->getMockBuilder(HasherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $hasher->expects($this->once())
            ->method('verify')
            ->with($this->isString(), 'hash')
            ->willReturn(true);

        $authManager = new AuthManager(
            new AuthConfig(
                steganography: $steganography,
                hasher: $hasher
            )
        );

        $authStamp = new AuthStamp(
            token: 'expected_token',
            hash: 'hash',
        );
        $this->assertFalse($authManager->verifyStamp(__DIR__ . '/Stego/Lsb/image_encoded.png', $authStamp));
    }

    public function testVerifyStampInvalidImage(): void
    {
        $this->expectException(\RuntimeException::class);
        $authManager = new AuthManager(new AuthConfig());
        $authStamp   = new AuthStamp(
            token: 'expected_token',
            hash: 'hash',
        );
        $authManager->verifyStamp('invalid_image.jpg', $authStamp);
    }
}
