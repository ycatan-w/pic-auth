<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Selector;

/**
 * AbstractChannelSelector class.
 */
abstract class AbstractChannelSelector implements ChannelSelectorInterface
{
    /**
     * @param  \GdImage $img
     * @param  string   $payloadBits
     *
     * @return void
     */
    public function validate(\GdImage $img, string $payloadBits): void
    {
        $width   = imagesx($img);
        $height  = imagesy($img);
        $maxBits = $width * $height * $this->getChannelsNumber();
        if (strlen($payloadBits) > $maxBits) {
            throw new \Exception('Payload is too long to be added into the image.');
        }
    }

    /**
     * @return int
     */
    abstract protected function getChannelsNumber(): int;
}
