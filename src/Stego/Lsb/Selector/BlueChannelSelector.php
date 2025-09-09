<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Selector;

/**
 * BlueChannelSelector class.
 */
class BlueChannelSelector extends AbstractChannelSelector
{
    /**
     * @return int
     */
    protected function getChannelsNumber(): int
    {
        return 1;
    }

    /**
     * @return array
     */
    public function getChannels(): array
    {
        return ['blue'];
    }
}
