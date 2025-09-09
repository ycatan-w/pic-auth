<?php

declare(strict_types=1);

namespace Crayon\PicAuth;

use Crayon\PicAuth\Config\AuthConfig;

/**
 * AuthManager class.
 */
class AuthManager
{
    /**
     * @param  AuthConfig $config
     */
    public function __construct(
        private readonly AuthConfig $config,
    ) {
    }

    /**
     * @param  string    $imagePath
     *
     * @return AuthStamp
     */
    public function stamp(string $imagePath): AuthStamp
    {
        $token        = $this->config->tokenGenerator->generate($this->config->tokenLength);
        $stampedImage = $this->config->steganography->embed($imagePath, $token);
        $hash         = $this->config->hasher->hash($stampedImage);

        return new AuthStamp(
            token: $token,
            hash: $hash,
            stampedImage: $stampedImage,
        );
    }

    /**
     * @param  string    $imagePath
     * @param  AuthStamp $authStamp
     *
     * @return bool
     */
    public function verifyStamp(string $imagePath, AuthStamp $authStamp): bool
    {
        $encodedImage = @file_get_contents($imagePath);
        if (false === $encodedImage) {
            throw new \RuntimeException("Cannot read image at $imagePath");
        }

        if (!$this->config->hasher->verify(base64_encode($encodedImage), $authStamp->hash)) {
            return false;
        }

        $decodedToken = $this->config->steganography->extract($imagePath);

        return hash_equals($authStamp->token, $decodedToken);
    }
}
