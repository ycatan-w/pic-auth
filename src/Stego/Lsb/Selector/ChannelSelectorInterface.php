<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Selector;

/**
 * ChannelSelectorInterface interface.
 */
interface ChannelSelectorInterface
{
    /**
     * @param  \GdImage $img
     * @param  string   $payloadBits
     *
     * @return void
     */
    public function validate(\GdImage $img, string $payloadBits): void;

    /**
     * @return array
     */
    public function getChannels(): array;
}
