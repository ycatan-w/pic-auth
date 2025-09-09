<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Selector;

/**
 * RgbChannelSelector class.
 */
class RgbChannelSelector extends AbstractChannelSelector
{
    /**
     * @return int
     */
    protected function getChannelsNumber(): int
    {
        return 3;
    }

    /**
     * @return array
     */
    public function getChannels(): array
    {
        return ['red', 'green', 'blue'];
    }
}
