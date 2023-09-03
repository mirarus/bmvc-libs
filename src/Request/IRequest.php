<?php

/**
 * IRequest
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Request
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.4
 */

namespace BMVC\Libs\Request;

interface IRequest
{

  /**
   * @param string|null $key
   * @return mixed
   */
  public static function _server(string $key = null);

  /**
   * @param string|null $key
   * @param $default
   * @return mixed
   */
  public static function header(string $key = null, $default = null);

  /**
   * @return string
   */
  public static function getMethod(): string;

  /**
   * @return string
   */
  public static function getRequestMethod(): string;

  /**
   * @return bool
   */
  public static function isGet(): bool;

  /**
   * @return bool
   */
  public static function isPost(): bool;

  /**
   * @return bool
   */
  public static function isPut(): bool;

  /**
   * @return bool
   */
  public static function isPatch(): bool;

  /**
   * @return bool
   */
  public static function isDelete(): bool;

  /**
   * @return bool
   */
  public static function isHead(): bool;

  /**
   * @return bool
   */
  public static function isOptions(): bool;

  /**
   * @return bool
   */
  public static function isAjax(): bool;

  /**
   * @return bool
   */
  public static function isFormData(): bool;

	/**
	 * @param $content
	 *
	 * @return FormData
	 */
	public static function getFormData($content = null): FormData;

  /**
   * @return mixed
   */
  public static function getContentType();

  /**
   * @return mixed
   */
  public static function getMediaType();

  /**
   * @return array
   */
  public static function getMediaTypeParams(): array;

  /**
   * @return mixed
   */
  public static function getContentCharset();

  /**
   * @return int
   */
  public static function getContentLength(): int;

  /**
   * @return string
   */
  public static function getHost(): string;

  /**
   * @return int
   */
  public static function getPort(): int;

  /**
   * @return string
   */
  public static function getHostWithPort(): string;

  /**
   * @return string
   */
  public static function getScheme(): string;

  /**
   * @return string
   */
  public static function getScriptName(): string;

  /**
   * @return string
   */
  public static function getPathInfo();

  /**
   * @return string
   */
  public static function getPath(): string;

  /**
   * @return string
   */
  public static function getResourceUri();

  /**
   * @return string
   */
  public static function getUrl(): string;

  /**
   * @return string
   */
  public static function getIp(): string;

  /**
   * @return mixed
   */
  public static function getReferer();

  /**
   * @return string
   */
  public static function getUserAgent(): string;

  /**
   * @param string $domain
   * @return bool
   */
  public static function checkDomain(string $domain): bool;

  /**
   * @param $ip
   * @return bool
   */
  public static function checkIp($ip): bool;

  /**
   * @return mixed
   */
  public static function inputToPost();

	/**
   * @return mixed
   */
  public static function input();

	/**
   * @return mixed
   */
  public static function _input();

  /**
   * @param string|null $data
   * @param bool $db_filter
   * @param bool $xss_filter
   * @return mixed
   */
  public static function server(string $data = null, bool $db_filter = true, bool $xss_filter = true);

  /**
   * @param string|null $data
   * @param bool $db_filter
   * @param bool $xss_filter
   * @return mixed
   */
  public static function request(string $data = null, bool $db_filter = true, bool $xss_filter = true);

  /**
   * @param string|null $data
   * @return mixed
   */
  public static function env(string $data = null);

  /**
   * @param string|null $data
   * @return mixed
   */
  public static function session(string $data = null);

  /**
   * @param string|null $data
   * @return mixed
   */
  public static function cookie(string $data = null);

  /**
   * @param string|null $data
   * @param bool $xss_filter
   * @return mixed
   */
  public static function files(string $data = null, bool $xss_filter = true);

  /**
   * @param string|null $data
   * @param bool $db_filter
   * @param bool $xss_filter
   * @return mixed
   */
  public static function post(string $data = null, bool $db_filter = true, bool $xss_filter = true);

  /**
   * @param string|null $data
   * @param bool $db_filter
   * @param bool $xss_filter
   * @return mixed
   */
  public static function get(string $data = null, bool $db_filter = true, bool $xss_filter = true);

  /**
   * @param string|null $data
   * @param string $type
   * @param bool $db_filter
   * @param bool $xss_filter
   * @return mixed
   */
  public static function filter(string $data = null, string $type = 'post', bool $db_filter = true, bool $xss_filter = true);

  /**
   * @param string|null $method
   * @param string $body_type
   * @return object
   */
  public static function body(string $method = null, string $body_type = 'object'): object;
}