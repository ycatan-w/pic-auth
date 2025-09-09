<?php

declare(strict_types=1);

namespace Crayon\PicAuth\Stego\Lsb;

use Crayon\PicAuth\Stego\Lsb\Payload\DefaultPayloadCreator;
use Crayon\PicAuth\Stego\Lsb\Payload\PayloadCreatorInterface;
use Crayon\PicAuth\Stego\Lsb\Selector\ChannelSelectorFactory;
use Crayon\PicAuth\Stego\SteganographyInterface;

/**
 * LsbSteganography class.
 */
class LsbSteganography implements SteganographyInterface
{
    /**
     * @param  LsbMode                  $mode
     * @param  int|null                 $randomSeed
     * @param  PayloadCreatorInterface  $payloadCreator
     * @param  ChannelSelectorFactory   $channelSelectorFactory
     */
    public function __construct(
        private readonly LsbMode $mode = LsbMode::BLUE,
        private readonly ?int $randomSeed = null,
        private readonly PayloadCreatorInterface $payloadCreator = new DefaultPayloadCreator(),
        private readonly ChannelSelectorFactory $channelSelectorFactory = new ChannelSelectorFactory(),
    ) {
        if (LsbMode::RANDOM === $this->mode && null === $randomSeed) {
            throw new \Exception('Seed must be provided to use Random mode.');
        }
    }

    /**
     * @param string $imagePath
     * @param string $message
     *
     * @return string
     */
    public function embed(string $imagePath, string $message): string
    {
        $channelSelector = $this->channelSelectorFactory->create($this->mode, $this->randomSeed);
        $payload         = $this->payloadCreator->create();
        $bits            = $payload->format($message);
        $img             = $this->loadImage($imagePath);

        $channelSelector->validate($img, $bits);

        $width  = imagesx($img);
        $height = imagesy($img);

        $pos = 0;
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                if ($pos >= strlen($bits)) {
                    break 2;
                }
                $rgb     = imagecolorat($img, $x, $y);
                $colours = imagecolorsforindex($img, $rgb);

                foreach ($channelSelector->getChannels() as $channel) {
                    if ($pos >= strlen($bits)) {
                        break;
                    }
                    $binary                      = str_pad(decbin($colours[$channel]), 8, '0', STR_PAD_LEFT);
                    $binary[strlen($binary) - 1] = $bits[$pos];
                    $colours[$channel]           = bindec($binary);
                    ++$pos;
                }

                imagesetpixel($img, $x, $y,
                    imagecolorallocatealpha(
                        $img,
                        $colours['red'],
                        $colours['green'],
                        $colours['blue'],
                        $colours['alpha'] ?? 0
                    )
                );
            }
        }

        ob_start();
        imagepng($img, null, 9);
        $imageData = ob_get_clean();
        imagedestroy($img);

        return base64_encode($imageData);
    }

    /**
     * @param string $imagePath
     *
     * @return string
     */
    public function extract(string $imagePath): string
    {
        $channelSelector = $this->channelSelectorFactory->create($this->mode, $this->randomSeed);
        $payload         = $this->payloadCreator->create();
        $img             = $this->loadImage($imagePath);

        $width  = imagesx($img);
        $height = imagesy($img);

        $bits = '';
        $pos  = 0;

        $bitsNeeded = null;
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                $rgb     = imagecolorat($img, $x, $y);
                $colours = imagecolorsforindex($img, $rgb);

                foreach ($channelSelector->getChannels() as $channel) {
                    $binary = str_pad(decbin($colours[$channel]), 8, '0', STR_PAD_LEFT);
                    $bits .= $binary[strlen($binary) - 1];
                    ++$pos;

                    if (32 === $pos && null === $bitsNeeded) {
                        $length     = bindec($bits);
                        $bitsNeeded = 32 + 16 + $length; // header + checksum + message
                    }

                    if (null !== $bitsNeeded && $pos >= $bitsNeeded) {
                        break 3;
                    }
                }
            }
        }

        imagedestroy($img);

        return $payload->parse($bits);
    }

    /**
     * @param  string   $imagePath
     *
     * @return \GdImage
     */
    private function loadImage(string $imagePath): \GdImage
    {
        $type = exif_imagetype($imagePath);

        return match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($imagePath) ?: throw new \Exception('Unable to create image'),
            IMAGETYPE_PNG  => imagecreatefrompng($imagePath) ?: throw new \Exception('Unable to create image'),
            default        => throw new \Exception('Unsupported image type'),
        };
    }
}
