<?php

declare(strict_types=1);

namespace Crayon\PicAuth;

/**
 * AuthStamp class.
 */
class AuthStamp
{
    /**
     * @param  string      $token
     * @param  string      $hash
     * @param  string|null $stampedImage
     */
    public function __construct(
        public readonly string $token,
        public readonly string $hash,
        public readonly ?string $stampedImage = null,
    ) {
    }
}
