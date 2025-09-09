<?php

declare(strict_types=1);

use Crayon\PicAuth\AuthManager;
use Crayon\PicAuth\AuthStamp;
use Crayon\PicAuth\Config\AuthConfig;

require __DIR__ . '/../vendor/autoload.php';

echo "=== REGISTER ===\n";
$inputImg  = __DIR__ . '/original.png';
$outputImg = __DIR__ . '/stamped.png';
if (file_exists($outputImg)) {
    unlink($outputImg);
}
echo 'Original image: ' . basename($inputImg) . "\n";
echo 'Stamped image:  ' . basename($outputImg) . "\n";
$authManager = new AuthManager(new AuthConfig());
try {
    $authStamp = $authManager->stamp($inputImg);
} catch (Exception $e) {
    echo "Status:         \033[31m✖ Fail (" . $e->getMessage() . ")\033[0m\n";
    exit(1);
}
$decodedImage = base64_decode($authStamp->stampedImage, true);
if (false === $decodedImage) {
    echo "Status:         \033[31m✖ Fail (Failed to decode stamped image)\033[0m\n";
    exit(1);
}
file_put_contents($outputImg, $decodedImage);
echo 'Token:          ' . $authStamp->token . "\n";
echo 'Token length:   ' . strlen($authStamp->token) . "\n";
echo 'Hash:           ' . $authStamp->hash . "\n";
echo 'Hash length:    ' . strlen($authStamp->hash) . "\n";
echo "Status:         \033[32m✔ Success\033[0m\n";

echo "\n";
echo "=== VERIFY ===\n";
echo 'Verifying stamped image: ' . basename($outputImg) . "\n";
$newAuthStamp = new AuthStamp(token: $authStamp->token, hash: $authStamp->hash);
try {
    $isValid = $authManager->verifyStamp($outputImg, $newAuthStamp);
} catch (Exception $e) {
    echo "Status:                  \033[31m✖ Fail (Login failed: " . $e->getMessage() . ")\033[0m\n";
    exit(1);
}

if ($isValid) {
    echo "Status:                  \033[32m✔ Success\033[0m\n";
} else {
    echo "Status:                  \033[31m✖ Fail (The image cannot be authenticated)\033[0m\n";
}

// comment the following lines to keep the encoded image
if (file_exists($outputImg)) {
    unlink($outputImg);
}
