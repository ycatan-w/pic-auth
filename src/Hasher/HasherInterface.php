<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Hasher;

/**
 * HasherInterface interface.
 */
interface HasherInterface
{
    /**
     * @param  string|null $pepper
     *
     * @return void
     */
    public function addPepper(?string $pepper): void;

    /**
     * Generate hash from data (text or image).
     *
     * @param string $data
     *
     * @return string
     */
    public function hash(string $data): string;

    /**
     * Verify hash validity against the data.
     *
     * @param string $data
     * @param string $hash
     *
     * @return bool
     */
    public function verify(string $data, string $hash): bool;
}
