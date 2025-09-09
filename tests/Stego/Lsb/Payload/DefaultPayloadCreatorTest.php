<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Stego\Lsb\Payload;

use Crayon\PicAuth\Stego\Lsb\Payload\DefaultPayloadCreator;
use Crayon\PicAuth\Stego\Lsb\Payload\Payload;
use PHPUnit\Framework\TestCase;

class DefaultPayloadCreatorTest extends TestCase
{
    public function testCreate(): void
    {
        $payload = (new DefaultPayloadCreator())->create();
        $this->assertInstanceOf(Payload::class, $payload);
    }
}
