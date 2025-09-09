<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Payload;

use Crayon\PicAuth\Util\BitUtils;

/**
 * Payload class.
 */
class Payload implements PayloadInterface
{
    /**
     * @return string
     */
    public function format(string $message): string
    {
        $messageBits  = BitUtils::stringToBits($message);
        $lengthBits   = str_pad(decbin(strlen($messageBits)), 32, '0', STR_PAD_LEFT);
        $checksum     = $this->simpleChecksum($message);
        $checksumBits = str_pad(decbin($checksum), 16, '0', STR_PAD_LEFT);

        return $lengthBits . $checksumBits . $messageBits;
    }

    /**
     * @param  string $bits
     *
     * @return string
     */
    public function parse(string $bits): string
    {
        $length      = bindec(substr($bits, 0, 32));
        $checksum    = bindec(substr($bits, 32, 16));
        $messageBits = substr($bits, 48, $length);
        $message     = BitUtils::bitsToString($messageBits);

        if ($this->simpleChecksum($message) !== $checksum) {
            throw new \Exception('Checksum mismatch');
        }

        return $message;
    }

    /**
     * @param  string $data
     *
     * @return int
     */
    private function simpleChecksum(string $data): int
    {
        $sum = 0;
        for ($i = 0; $i < strlen($data); ++$i) {
            $sum += ord($data[$i]);
        }

        return $sum % 65536;
    }
}
