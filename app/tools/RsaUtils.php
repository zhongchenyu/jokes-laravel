<?php

namespace App\Tools;

/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/23
 * Time: 21:27
 */

class RsaUtils {

  public static function enPublic($data)
  {
    $path = base_path();
    $publicKey = openssl_get_publickey(file_get_contents($path.'/sec/rsa_public_key.pem'));
    openssl_public_encrypt($data,$encrypted,$publicKey);
    $base64Encoded = base64_encode($encrypted);
    return $base64Encoded;
  }

  public static function dePrivate($data)
  {
    $path = base_path();
    $privateKey = openssl_get_privatekey(file_get_contents($path.'/sec/rsa_private_key.pem'));
    openssl_private_decrypt(base64_decode($data), $decrypted, $privateKey);

    return $decrypted;
  }

  public static function enPrivate($data) {
    $path = base_path();
    $privateKey = openssl_get_privatekey(file_get_contents($path.'/sec/rsa_private_key.pem'));
    openssl_private_encrypt($data, $encrypted, $privateKey);
    $base64Encoded = base64_encode($encrypted);
    return $base64Encoded;
  }

  public static function dePublic($data) {
    $path = base_path();
    $publicKey = openssl_get_publickey(file_get_contents($path.'/sec/rsa_public_key.pem'));
    openssl_public_decrypt(base64_decode($data), $decrypted, $publicKey);
    return $decrypted;
  }

}