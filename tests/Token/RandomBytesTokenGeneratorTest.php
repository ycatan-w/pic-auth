<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Token;

use Crayon\PicAuth\Token\RandomBytesTokenGenerator;
use PHPUnit\Framework\TestCase;

class RandomBytesTokenGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $token = (new RandomBytesTokenGenerator())->generate(16);
        $this->assertNotEmpty($token);
        $this->assertSame(16, strlen($token));
        $this->assertMatchesRegularExpression('/^[0-9a-f]+$/', $token);
    }
}
