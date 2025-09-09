<?php

declare(strict_types=1);

namespace Tests\Crayon\PicAuth\Hasher;

use Crayon\PicAuth\Hasher\Sha256Hasher;
use PHPUnit\Framework\TestCase;

class Sha256HasherTest extends TestCase
{
    public function testHasher(): void
    {
        $hasher = new Sha256Hasher();

        $this->assertEquals('a591a6d40bf420404a011733cfb7b190d62c65bf0bcda32b57b277d9ad9f146e', $hasher->hash('Hello World'));
        $this->assertTrue($hasher->verify('Hello World', 'a591a6d40bf420404a011733cfb7b190d62c65bf0bcda32b57b277d9ad9f146e'));
        $this->assertFalse($hasher->verify('Hello World', '8380c4c6720e0d5ce4789bf72df03a6e1b3ed80891f3adbe8833c760399b8e91'));

        $hasher->addPepper('spice your life');
        $this->assertEquals('ddc49849f636e5acfbed5a33906eae7f5c600e182088e280a390525b2ce44e5a', $hasher->hash('Hello World'));
        $this->assertTrue($hasher->verify('Hello World', 'ddc49849f636e5acfbed5a33906eae7f5c600e182088e280a390525b2ce44e5a'));
        $this->assertFalse($hasher->verify('Hello World', '8d452bd5ff7c3fb01f65aa98464a327571ec806581b5655a30d08f60fb17e8be'));
    }
}
