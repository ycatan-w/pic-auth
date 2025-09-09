<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Payload;

/**
 * PayloadCreatorInterface interface.
 */
interface PayloadCreatorInterface
{
    /**
     * @return PayloadInterface
     */
    public function create(): PayloadInterface;
}
