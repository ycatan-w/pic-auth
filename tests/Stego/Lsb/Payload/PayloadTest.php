<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Stego\Lsb\Payload;

use Crayon\PicAuth\Stego\Lsb\Payload\Payload;
use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    public function testPayload(): void
    {
        $payload = new Payload();
        $this->assertEquals('0000000000000000000000000111000000000100011111110100100001100101011011000110110001101111001000000101011101101111011100100110110001100100001000010010000100100001', $payload->format('Hello World!!!'));
        $this->assertEquals('Hello World!!!', $payload->parse('0000000000000000000000000111000000000100011111110100100001100101011011000110110001101111001000000101011101101111011100100110110001100100001000010010000100100001'));
    }

    public function testPayloadParseFailure(): void
    {
        $this->expectException(\Exception::class);
        $payload = new Payload();
        $payload->parse('0000000000000000000000000111000000000100011111110100100001100110011011000110110001101111001000000101011101101111011100100110110001100100001000010010000100100001');
    }
}
