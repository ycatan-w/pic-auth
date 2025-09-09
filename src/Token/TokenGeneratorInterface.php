<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Token;

/**
 * TokenGeneratorInterface interface.
 */
interface TokenGeneratorInterface
{
    /**
     * @param  int    $length
     *
     * @return string
     */
    public function generate(int $length): string;
}
