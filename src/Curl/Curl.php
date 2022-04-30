<?php

/**
 * Curl
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Curl
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace BMVC\Libs\Curl;

class Curl
{

  /**
   * @var
   */
  private static $ch;

  /**
   * @var
   */
  private static $error;

  /**
   * @var bool
   */
  private static $followRedirects = true;

  /**
   * @var array
   */
  private static $options = [];

  /**
   * @var array
   */
  private static $headers = [];

  /**
   * @var null
   */
  private static $referrer = null;

  /**
   * @var bool
   */
  private static $useCookie = false;

  /**
   * @var string
   */
  private static $cookieFile;

  /**
   * @var mixed|string
   */
  private static $userAgent;

  /**
   * @var
   */
  private static $responseBody;

  /**
   * @var array
   */
  private static $responseHeader = [];

  public function __construct()
  {
    if (self::$useCookie) {
      self::$cookieFile = 'curl_cookie.txt';
    }
    if (self::$userAgent == null) {
      self::$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'PHP/BMVC';
    }
  }

  /**
   * @param string $url
   * @param array $params
   * @return void
   */
  public static function head(string $url, array $params = []): void
  {
    self::request('HEAD', $url, $params);
  }

  /**
   * @param string $url
   * @param $params
   * @return void
   */
  public static function get(string $url, $params): void
  {
    if (!empty($params)) {
      $url .= (stripos($url, '?') !== false) ? '&' : '?';
      $url .= (is_string($params)) ? $params : http_build_query($params, '', '&');
    }
    self::request('GET', $url, []);
  }

  /**
   * @param string $url
   * @param array $params
   * @return void
   */
  public static function post(string $url, array $params = []): void
  {
    self::request('POST', $url, $params);
  }

  /**
   * @param string $url
   * @param array $params
   * @return void
   */
  public static function put(string $url, array $params = []): void
  {
    self::request('PUT', $url, $params);
  }

  /**
   * @param string $url
   * @param array $params
   * @return void
   */
  public static function delete(string $url, array $params = []): void
  {
    self::request('DELETE', $url, $params);
  }

  /**
   * @param string|null $key
   * @return array|mixed|null
   */
  public static function responseHeader(string $key = null)
  {
    if ($key == null) {
      return self::$responseHeader;
    } else {
      if (array_key_exists($key, self::$responseHeader)) {
        return self::$responseHeader[$key];
      } else {
        return null;
      }
    }
  }

  /**
   * @return string
   */
  public static function responseBody(): string
  {
    return self::$responseBody;
  }

  /**
   * @param string $agent
   * @return string
   */
  public static function setUserAgent(string $agent): string
  {
    return self::$userAgent = $agent;
  }

  /**
   * @param string $referrer
   * @return string
   */
  public static function setReferrer(string $referrer): string
  {
    return self::$referrer = $referrer;
  }

  /**
   * @param $header
   * @param $val
   * @return array
   */
  public static function setHeader($header, $val = null): array
  {
    if (is_array($header)) {
      self::$headers = $header;
    } else {
      self::$headers[$header] = $val;
    }
    return self::$headers;
  }

  /**
   * @param $options
   * @param $val
   * @return array
   */
  public static function setOptions($options, $val = null): array
  {
    if (is_array($options)) {
      self::$options = $options;
    } else {
      self::$options[$options] = $val;
    }
    return self::$options;
  }

  /**
   * @param string $method
   * @param string $url
   * @param array $params
   * @return void
   */
  private static function request(string $method, string $url, array $params)
  {
    self::$error = '';
    self::$ch = curl_init();
    self::set_request_method($method);
    self::set_request_options($url, $params);
    self::set_request_headers();
    $response = curl_exec(self::$ch);
    if ($response) {
      return self::getResponse($response);
    } else {
      self::$error = curl_errno(self::$ch) . ' - ' . curl_error(self::$ch);
    }
    curl_close(self::$ch);
  }

  /**
   * @return void
   */
  private static function set_request_headers(): void
  {
    $headers = [];
    foreach (self::$headers as $key => $val) {
      $headers[] = $key . ': ' . $val;
    }
    curl_setopt(self::$ch, CURLOPT_HTTPHEADER, $headers);
  }

  /**
   * @param string $method
   * @return void
   */
  private static function set_request_method(string $method): void
  {
    if (strtoupper($method) == 'HEAD') {
      curl_setopt(self::$ch, CURLOPT_NOBODY, true);
    } elseif (strtoupper($method) == 'GET') {
      curl_setopt(self::$ch, CURLOPT_HTTPGET, true);
    } elseif (strtoupper($method) == 'POST') {
      curl_setopt(self::$ch, CURLOPT_POST, true);
    } else {
      curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, $method);
    }
  }

  /**
   * @param string $url
   * @param array $params
   * @return void
   */
  private static function set_request_options(string $url, array $params): void
  {
    curl_setopt(self::$ch, CURLOPT_URL, $url);
    if (!empty($params)) {
      curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $params);
    }
    curl_setopt(self::$ch, CURLOPT_HEADER, true);
    curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(self::$ch, CURLOPT_USERAGENT, self::$userAgent);
    if (self::$useCookie) {
      curl_setopt(self::$ch, CURLOPT_COOKIEFILE, self::$cookieFile);
      curl_setopt(self::$ch, CURLOPT_COOKIEJAR, self::$cookieFile);
    }
    if (self::$followRedirects) {
      curl_setopt(self::$ch, CURLOPT_FOLLOWLOCATION, true);
    }
    if (self::$referrer !== null) {
      curl_setopt(self::$ch, CURLOPT_REFERER, self::$referrer);
    }
    foreach (self::$options as $option => $val) {
      curl_setopt(self::$ch, constant('CURLOPT_' . str_replace('CURLOPT_', '', strtoupper($option))), $val);
    }
  }

  /**
   * @param string $response
   * @return void
   */
  private static function getResponse(string $response): void
  {
    preg_match_all('#HTTP/\d\.\d.*?$.*?\r\n\r\n#ims', $response, $matches);
    $headers_string = array_pop($matches[0]);
    $headers = explode("\r\n", str_replace("\r\n\r\n", '', $headers_string));
    self::$responseBody = str_replace($headers_string, '', $response);
    $version_and_status = array_shift($headers);
    preg_match('#HTTP/(\d\.\d)\s(\d\d\d)\s(.*)#', $version_and_status, $matches);
    self::$responseHeader['Http-Version'] = $matches[1];
    self::$responseHeader['Status-Code'] = $matches[2];
    self::$responseHeader['Status'] = $matches[2] . ' ' . $matches[3];
    foreach ($headers as $header) {
      preg_match('#(.*?)\:\s(.*)#', $header, $matches);
      self::$responseHeader[$matches[1]] = $matches[2];
    }
  }

  /**
   * @return string
   */
  public static function getError(): string
  {
    return self::$error;
  }
}