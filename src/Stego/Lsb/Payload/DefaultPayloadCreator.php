<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Payload;

/**
 * DefaultPayloadCreator class.
 */
class DefaultPayloadCreator implements PayloadCreatorInterface
{
    /**
     * @return Payload
     */
    public function create(): Payload
    {
        return new Payload();
    }
}
