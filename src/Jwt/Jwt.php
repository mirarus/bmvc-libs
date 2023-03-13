<?php

/**
 * JWT
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Jwt
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\Jwt;

use Exception;
use DateTime;

class Jwt
{

  /**
   * @var int
   */
  public static $exp = 300;

  /**
   * @var int
   */
  public static $leeway = 0;

  /**
   * @var string[]
   */
  private static $algorithms = [
    'HS256' => 'SHA256',
    'HS512' => 'SHA512',
    'HS384' => 'SHA384'
  ];

  /**
   * @param array $payload
   * @param string $secret
   * @param string $alg
   * @param array|null $head
   * @return string
   * @throws Exception
   */
  public static function encode(array $payload, string $secret, string $alg = 'HS256', array $head = null): string
  {
    $header = [
      'typ' => 'JWT',
      'alg' => $alg
    ];
    if ($head != null) {
      $header = array_merge($head, $header);
    }
    $payload['jwt']['exp'] = time() + self::$exp;
    $payload['jwt']['jti'] = uniqid((string)time());
    $payload['jwt']['iat'] = time();
    $header = self::urlSafeBase64Encode(self::jsonEncode($header));
    $payload = self::urlSafeBase64Encode(self::jsonEncode($payload));
    $message = $header . '.' . $payload;
    $signature = self::urlSafeBase64Encode(self::signature($message, $secret, $alg));
    return $header . '.' . $payload . '.' . $signature;
  }


  /**
   * @param string $token
   * @param string|null $secret
   * @return mixed
   * @throws Exception
   */
  public static function decode(string $token, string $secret = null)
  {
    if (empty($secret)) {
      throw new Exception('JWT Error! | Secret may not be empty.');
    }
    $jwt = explode('.', $token);
    if (count($jwt) != 3) {
      throw new Exception('JWT Error! | Wrong number of segments.');
    }
    list ($head64, $payload64, $sign64) = $jwt;
    if (null === ($header = self::jsonDecode(self::urlSafeBase64Decode($head64)))) {
      throw new Exception('JWT Error! | Invalid header encoding.');
    }
    if (null === ($payload = self::jsonDecode(self::urlSafeBase64Decode($payload64)))) {
      throw new Exception('JWT Error! | Invalid claims encoding.');
    }
    if (!($signature = self::urlSafeBase64Decode($sign64))) {
      throw new Exception('JWT Error! | Invalid signature encoding.');
    }
    if (empty($header->alg)) {
      throw new Exception('JWT Error! | Empty algorithm.');
    }
    if (empty(self::$algorithms[$header->alg])) {
      throw new Exception('JWT Error! | Algorithm not supported.');
    }
    if (!self::verify("$head64.$payload64", $signature, $secret, $header->alg)) {
      throw new Exception('JWT Error! | Signature verification failed.');
    }
    if (isset($payload->jwt->iat) && $payload->jwt->iat > (time() + self::$leeway)) {
      throw new Exception('JWT Error! | Cannot handle token prior to ' . date(DateTime::ISO8601, $payload->jwt->iat));
    }
    if (isset($payload->jwt->exp) && (time() - self::$leeway) >= $payload->jwt->exp) {
      throw new Exception('JWT Error! | Expired token.');
    }
    return $payload;
  }

  /**
   * @param string $message
   * @param string $secret
   * @param string $alg
   * @return false|string
   * @throws Exception
   */
  private static function signature(string $message, string $secret, string $alg)
  {
    if (!array_key_exists($alg, self::$algorithms)) {
      throw new Exception('JWT Error! | Algorithm not supported.');
    }
    return hash_hmac(self::$algorithms[$alg], $message, $secret, true);
  }

  /**
   * @param string $message
   * @param string $signature
   * @param string $secret
   * @param string $alg
   * @return bool
   * @throws Exception
   */
  private static function verify(string $message, string $signature, string $secret, string $alg): bool
  {
    if (empty(self::$algorithms[$alg])) {
      throw new Exception('JWT Error! | Algorithm not supported.');
    }
    $hash = hash_hmac(self::$algorithms[$alg], $message, $secret, true);
    if (function_exists('hash_equals')) {
      return hash_equals($signature, $hash);
    }
    $len = min(self::safeStrLen($signature), self::safeStrLen($hash));
    $status = 0;
    for ($i = 0; $i < $len; $i++) {
      $status |= (ord($signature[$i]) ^ ord($hash[$i]));
    }
    $status |= (self::safeStrLen($signature) ^ self::safeStrLen($hash));
    return ($status === 0);
  }

  /**
   * @param string $data
   * @return string
   */
  private static function urlSafeBase64Encode(string $data): string
  {
    return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
  }

  /**
   * @param string $data
   * @return string
   */
  private static function urlSafeBase64Decode(string $data): string
  {
    $remainder = strlen($data) % 4;
    if ($remainder) {
      $padlen = 4 - $remainder;
      $data .= str_repeat('=', $padlen);
    }
    return base64_decode(strtr($data, '-_', '+/'));
  }

  /**
   * @param $data
   * @return false|string
   * @throws Exception
   */
  private static function jsonEncode($data)
  {
    $json = json_encode($data);
    if (function_exists('json_last_error') && $errno = json_last_error()) {
      self::handleJsonError($errno);
    } elseif ($json === 'null' && $data !== null) {
      throw new Exception('JWT Error! | Null result with non-null input.');
    }
    return $json;
  }

  /**
   * @param string $data
   * @return mixed
   * @throws Exception
   */
  private static function jsonDecode(string $data)
  {
    $obj = json_decode($data, false, 512, JSON_BIGINT_AS_STRING);

    if (function_exists('json_last_error') && $errno = json_last_error()) {
      self::handleJsonError($errno);
    } elseif ($obj === null && $data !== 'null') {
      throw new Exception('JWT Error! | Null result with non-null input.');
    }
    return $obj;
  }

  /**
   * @param int $errno
   * @return mixed
   * @throws Exception
   */
  private static function handleJsonError(int $errno)
  {
    $messages = [
      JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
      JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
      JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
      JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
      JSON_ERROR_UTF8 => 'Malformed UTF-8 characters'
    ];

    throw new Exception('JWT Error! | ' . ($messages[$errno] ?? 'Unknown JSON error: ' . $errno));
  }


  /**
   * @param string $str
   * @return int
   */
  private static function safeStrLen(string $str): int
  {
    if (function_exists('mb_strlen')) {
      return mb_strlen($str, '8bit');
    }
    return strlen($str);
  }
}