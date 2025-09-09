<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Selector;

use Crayon\PicAuth\Stego\Lsb\LsbMode;

/**
 * ChannelSelectorFactory class.
 */
class ChannelSelectorFactory
{
    /**
     * @param  LsbMode  $mode
     * @param  int|null $seed
     *
     * @return ChannelSelectorInterface
     */
    public function create(LsbMode $mode, ?int $seed = null): ChannelSelectorInterface
    {
        return match ($mode) {
            LsbMode::RED    => new RedChannelSelector(),
            LsbMode::GREEN  => new GreenChannelSelector(),
            LsbMode::BLUE   => new BlueChannelSelector(),
            LsbMode::RGB    => new RgbChannelSelector(),
            LsbMode::RANDOM => new RandomChannelSelector($seed ?? throw new \InvalidArgumentException('Seed required')),
        };
    }
}
