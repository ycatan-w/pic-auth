<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Config;

use Crayon\PicAuth\Config\AuthConfig;
use Crayon\PicAuth\Hasher\HasherInterface;
use PHPUnit\Framework\TestCase;

class AuthConfigTest extends TestCase
{
    public function testConfig(): void
    {
        new AuthConfig();
        $this->assertTrue(true);

        $mockHasher = $this->getMockBuilder(HasherInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockHasher->expects($this->once())
            ->method('addPepper')
            ->with('spice your life');
        new AuthConfig(
            hasher: $mockHasher,
            pepper: 'spice your life',
        );
        $this->assertTrue(true);
    }

    public function testConfigFailure(): void
    {
        $this->expectException(\Exception::class);
        new AuthConfig(
            tokenLength: 0
        );
    }
}
