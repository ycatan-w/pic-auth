<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Payload;

/**
 * PayloadInterface interface.
 */
interface PayloadInterface
{
    /**
     * @return string
     */
    public function format(string $message): string;

    /**
     * @param  string $bits
     *
     * @return string
     */
    public function parse(string $payload): string;
}
