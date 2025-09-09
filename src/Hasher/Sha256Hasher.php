<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Hasher;

/**
 * Sha256Hasher class.
 */
class Sha256Hasher implements HasherInterface
{
    /**
     * @var string|null
     */
    protected ?string $pepper;

    /**
     * @param  string|null $pepper
     *
     * @return void
     */
    public function addPepper(?string $pepper): void
    {
        $this->pepper = $pepper;
    }

    /**
     * Generate hash from data (text or image).
     *
     * @param string $data
     *
     * @return string
     */
    public function hash(string $data): string
    {
        $dataToHash = ($this->pepper ?? '') . $data;

        return hash('sha256', $dataToHash);
    }

    /**
     * Verify hash validity against the data.
     *
     * @param string $data
     * @param string $hash
     *
     * @return bool
     */
    public function verify(string $data, string $hash): bool
    {
        $dataToHash = ($this->pepper ?? '') . $data;

        return hash('sha256', $dataToHash) === $hash;
    }
}
