<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth;

use Crayon\PicAuth\AuthStamp;
use PHPUnit\Framework\TestCase;

class AuthStampTest extends TestCase
{
    public function testStamps(): void
    {
        $stamp = new AuthStamp('token', 'hash', 'image');
        $this->assertEquals('token', $stamp->token);
        $this->assertEquals('hash', $stamp->hash);
        $this->assertEquals('image', $stamp->stampedImage);

        $stamp = new AuthStamp('token', 'hash', null);
        $this->assertEquals('token', $stamp->token);
        $this->assertEquals('hash', $stamp->hash);
        $this->assertNull($stamp->stampedImage);
    }
}
