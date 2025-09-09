<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Token;

/**
 * RandomBytesTokenGenerator class.
 */
class RandomBytesTokenGenerator implements TokenGeneratorInterface
{
    /**
     * @param  int    $length
     *
     * @return string
     */
    public function generate(int $length): string
    {
        return bin2hex(random_bytes((int) ($length / 2)));
    }
}
