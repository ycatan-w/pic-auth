<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego;

/**
 * SteganographyInterface interface.
 */
interface SteganographyInterface
{
    /**
     * Add message into an image.
     *
     * @param string $imagePath
     * @param string $message
     *
     * @throws SteganographyException
     * @return string
     */
    public function embed(string $imagePath, string $message): string;

    /**
     * Extract message from an image.
     *
     * @param string $imagePath
     *
     * @throws SteganographyException
     * @return string
     */
    public function extract(string $imagePath): string;
}
