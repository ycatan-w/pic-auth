# PicAuth

**PicAuth** is a PHP library for image-based authentication using a “stamp” system.
Why rely on traditional passwords, SSO, or phone numbers when a simple image can do the job? With PicAuth, your image itself becomes the key: a unique “stamp” is embedded into it, allowing secure authentication without the need for text-based credentials. It’s fun, visual, and intuitive—perfect for playful projects or prototypes.

**⚠️ Note:** This library is intended for fun and experimental purposes. It is **not recommended for production or security-critical projects**.

---

## Features

- Embed secret tokens into images (PNG/JPEG) using **LSB steganography**
- Verify images and tokens
- Configurable modes for LSB embedding:
  - `RED`, `GREEN`, `BLUE`, `RGB`, `RANDOM`
- Checksum validation for data integrity
- Fully object-oriented and testable
- Ready-to-use Symfony bundle for easy integration (comming soon)

---

## Installation

Before installing PicAuth, you must add the repository to your `composer.json`:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "git@github.com:ycatan-w/pic-auth.git"
    }
  ]
}
```

Then install via Composer:

```bash
composer require crayon/pic-auth
```

---

## Usage

Example scripts demonstrating how to use PicAuth can be found in the `examples` folder of the repository.

### 1. Add a Stamp (Register)

```php
use Crayon\PicAuth\AuthManager;
use Crayon\PicAuth\Config\AuthConfig;

require __DIR__ . '/vendor/autoload.php';

$inputImg  = __DIR__ . '/original.png';
$outputImg = __DIR__ . '/stamped.png';

$authManager = new AuthManager(new AuthConfig());

$authStamp = $authManager->stamp($inputImg);

echo "Token: {$authStamp->token}\n";
echo "Hash: {$authStamp->hash}\n";

file_put_contents($outputImg, base64_decode($authStamp->stampedImage));
```

### 2. Verify a Stamp (Login)

```php
use Crayon\PicAuth\AuthStamp;

$newAuthStamp = new AuthStamp(token: $authStamp->token, hash: $authStamp->hash);

$isValid = $authManager->verifyStamp($outputImg, $newAuthStamp);

if ($isValid) {
    echo "✅ Stamp verified\n";
} else {
    echo "❌ Stamp verification failed\n";
}
```

---

## Configuration

`AuthConfig` allows you to configure:

- `tokenLength`: Length of the generated token
- Other options can be added as needed (future-proof)

Example:

```php
$config = new AuthConfig([
    'tokenLength' => 32,
]);
$authManager = new AuthManager($config);
```

---

## LSB Steganography Modes

| Mode   | Description                                             |
| ------ | ------------------------------------------------------- |
| RED    | Embed in the red channel of pixels                      |
| GREEN  | Embed in the green channel                              |
| BLUE   | Embed in the blue channel                               |
| RGB    | Embed across all three channels                         |
| RANDOM | Randomly select one channel per pixel (requires a seed) |

---

## Notes

- Supports **PNG** and **JPEG** images
- Images are **not saved to disk** by default; returned as Base64
- Checksum ensures the integrity of the hidden message
- Seed required for RANDOM mode to reproduce the same sequence

---

## Testing

The library uses **PHPUnit** for unit testing:

```bash
composer install
XDEBUG_MODE=coverage vendor/bin/phpunit -c phpunit.xml.dist
```
