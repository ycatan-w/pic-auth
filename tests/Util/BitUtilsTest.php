<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Util;

use Crayon\PicAuth\Util\BitUtils;
use PHPUnit\Framework\TestCase;

class BitUtilsTest extends TestCase
{
    public function testBitUtils(): void
    {
        $this->assertEquals('0100100001100101011011000110110001101111001000000101011101101111011100100110110001100100001000010010000100100001', BitUtils::stringToBits('Hello World!!!'));
        $this->assertEquals('Hello World!!!', BitUtils::bitsToString('0100100001100101011011000110110001101111001000000101011101101111011100100110110001100100001000010010000100100001'));
        $msgInBits = BitUtils::stringToBits('Hello World!!!');
        $this->assertEquals('Hello World!!!', BitUtils::bitsToString($msgInBits));
    }
}
