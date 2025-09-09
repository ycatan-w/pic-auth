<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Util;

/**
 * BitUtils class.
 */
class BitUtils
{
    /**
     * @param  string $text
     *
     * @return string
     */
    public static function stringToBits(string $text): string
    {
        $bits  = '';
        $bytes = unpack('C*', $text);
        foreach ($bytes as $byte) {
            $bits .= str_pad(decbin($byte), 8, '0', STR_PAD_LEFT);
        }

        return $bits;
    }

    /**
     * @param  string $bits
     *
     * @return string
     */
    public static function bitsToString(string $bits): string
    {
        $bytes = [];
        for ($i = 0; $i < strlen($bits); $i += 8) {
            $bytes[] = bindec(substr($bits, $i, 8));
        }

        return pack('C*', ...$bytes);
    }
}
