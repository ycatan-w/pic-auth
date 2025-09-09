<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb;

/**
 * LsbMode Enums.
 */
enum LsbMode: string
{
    case RED    = 'red';
    case GREEN  = 'green';
    case BLUE   = 'blue';
    case RGB    = 'rgb';
    case RANDOM = 'random';
}
