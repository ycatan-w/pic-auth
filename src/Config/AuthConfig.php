<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Config;

use Crayon\PicAuth\Hasher\HasherInterface;
use Crayon\PicAuth\Hasher\Sha256Hasher;
use Crayon\PicAuth\Stego\Lsb\LsbSteganography;
use Crayon\PicAuth\Stego\SteganographyInterface;
use Crayon\PicAuth\Token\RandomBytesTokenGenerator;
use Crayon\PicAuth\Token\TokenGeneratorInterface;

/**
 * AuthConfig class.
 */
class AuthConfig
{
    /**
     * @param  SteganographyInterface $steganography
     * @param  HasherInterface        $hasher
     * @param  int                    $tokenLength
     * @param  mixed                  $pepper
     */
    public function __construct(
        public readonly SteganographyInterface $steganography = new LsbSteganography(),
        public readonly HasherInterface $hasher = new Sha256Hasher(),
        public readonly TokenGeneratorInterface $tokenGenerator = new RandomBytesTokenGenerator(),
        public readonly int $tokenLength = 32,
        public readonly ?string $pepper = null,
    ) {
        if ($this->tokenLength <= 0) {
            throw new \InvalidArgumentException('Token length must be positive');
        }
        $this->hasher->addPepper($pepper);
    }
}
