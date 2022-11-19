<?php

/**
 * Response
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Response
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace BMVC\Libs\Response;

class Response
{

  /**
   * @var string[]
   */
  public static $statusCodes = [
    100 => 'Continue',
    101 => 'Switching Protocols',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    306 => '(Unused)',
    307 => 'Temporary Redirect',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Requested Range Not Satisfiable',
    417 => 'Expectation Failed',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported'
  ];

  /**
   * @param int $code
   * @return void
   */
  public static function setHeader(int $code)
  {
    @http_response_code($code);
    @header("HTTP/1.1 " . $code . " " . self::setStatusCode($code));
    @header("Content-Type: application/json; charset=utf-8");
  }

  /**
   * @param int $code
   * @return void
   */
  public static function setStatusCode(int $code)
  {
    http_response_code($code);
  }

  /**
   * @return int
   */
  public static function getStatusCode(): int
  {
    return (int)http_response_code();
  }

  /**
   * @param int|null $code
   * @return string
   */
  public static function getStatusMessage(int $code = null): string
  {
    if (is_null($code)) {
      return self::$statusCodes[self::getStatusCode()];
    }
    return self::$statusCodes[$code];
  }

  /**
   * @param $data
   * @param bool $status
   * @param int $code
   * @param bool $cache
   * @return false|string
   */
  public static function json($data = null, bool $status = true, int $code = 200, bool $cache = true)
  {
    self::setHeader($code);
    if ($cache == true) @header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    //@header('Status: ' . self::$statusCodes[$code]);
    return json_encode(['status' => $status, 'message' => $data]);
  }

  /**
   * @param array|null $data
   * @param int $code
   * @param bool $cache
   * @return false|string
   */
  public static function _json(array $data = null, int $code = 200, bool $cache = true)
  {
    if ($data == null) $data = [];
    self::setHeader($code);
    if ($cache == true) @header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    return json_encode($data);
  }
}