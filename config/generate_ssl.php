<?php

require __DIR__ . '/../vendor/autoload.php';

use phpseclib3\Crypt\RSA;

$rsa = RSA::createKey(2048);

file_put_contents(__DIR__ . '/private_key.pem', $rsa->toString('PKCS8'));
file_put_contents(__DIR__ . '/public_key.pem', $rsa->getPublicKey()->toString('PKCS8'));

echo "RSA key generated\n";
