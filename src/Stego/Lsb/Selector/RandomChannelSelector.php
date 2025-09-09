<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb\Selector;

use Random\Engine\Mt19937;
use Random\Randomizer;

/**
 * RandomChannelSelector class.
 */
class RandomChannelSelector extends AbstractChannelSelector
{
    /**
     * @var Randomizer
     */
    private Randomizer $randomizer;

    /**
     * @param  int $seed
     */
    public function __construct(int $seed)
    {
        $this->randomizer = new Randomizer(new Mt19937($seed));
    }

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
        $channels = ['red', 'green', 'blue'];

        return [$channels[$this->randomizer->getInt(0, 2)]];
    }
}
