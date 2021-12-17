<?php

/**
 * Util
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Util
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace BMVC\Libs\Util;

use stdClass;

class Util
{

	/**
	 * @var string
	 */
	private static $urlGetName = 'url';
	
	/**
	 * @return string
	 */
	public static function get_url(): string
	{
		$url = '';

		if (isset($_GET[self::$urlGetName])) {
			$url = $_GET[self::$urlGetName];
		} elseif (isset($_SERVER['PATH_INFO'])) {
			$url = $_SERVER['PATH_INFO'];
		}

		return trim(trim($url), '/');
	}

	/**
	 * @return string
	 */
	public static function page_url(): string
	{
		$url = '';

		if (isset($_ENV['DIR'])) {
			$url = str_replace($_ENV['DIR'], "", trim($_SERVER['REQUEST_URI']));
		} elseif (isset($_GET[self::$urlGetName])) {
			$url = $_GET[self::$urlGetName];
		} elseif (isset($_SERVER['PATH_INFO'])) {
			$url = $_SERVER['PATH_INFO'];
		}

		return trim($url, '/');
	}

	/**
	 * @param string|null  $url
	 * @param bool|boolean $atRoot
	 * @param bool|boolean $atCore
	 * @param bool|boolean $parse
	 *
	 * @return (int|string)[]|false|string
	 *
	 * @psalm-return array{scheme?: string, user?: string, pass?: string, host?: string, port?: int, path?: string, query?: string, fragment?: string}|false|string
	 */
	public static function base_url(string $url = null, bool $atRoot = false, bool $atCore = false, bool $parse = false)
	{
		if (isset($_SERVER['HTTP_HOST'])) {
			$http = (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || $_SERVER['SERVER_PORT'] == 443 || (isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443)) ? 'https' : 'http');
			$hostname = $_SERVER['HTTP_HOST'];

			$dir  = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
			$core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', (string) realpath(dirname(dirname(__FILE__)))), null, PREG_SPLIT_NO_EMPTY);

			$core = $core[0];  // @phpstan-ignore-line
			$tmplt = $atRoot ? ($atCore ? '%s://%s/%s/' : '%s://%s/') : ($atCore ? '%s://%s/%s/' : '%s://%s%s');
			$end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
			$base_url = sprintf($tmplt, $http, $hostname, $end);
		} else {
			$base_url = 'http://localhost/';
		}

		$base_url = rtrim($base_url, '/');
		if (!empty($url)) $base_url .= $url;

		$base_url = @str_replace(trim(@$_ENV['PUBLIC_DIR'], '/'), "", rtrim($base_url, '/'));
		$base_url = trim($base_url, '/') . '/';

		if ($parse) {
			$base_url = parse_url($base_url);
			if (trim((string) self::base_url(), '/') == $base_url) $base_url['path'] = '/';  // @phpstan-ignore-line
		}
		return $base_url;
	}

	/**
	 * @param  string|null  $url
	 * @param  bool|boolean $print
	 * @param  bool|boolean $cache
	 * @return null|string
	 */
	public static function url(string $url = null, bool $print = false, bool $cache = false)
	{
		$burl = self::base_url();
		$cach = ($cache ? ('?ct=' . time()) : null);
		$_url = (($url ? ($burl . $url) : $burl) . $cach); // @phpstan-ignore-line

		if ($print == true) { // @phpstan-ignore-line
			echo $_url;
		} else {
			return $_url;
		}
	}

	/**
	 * @param  array        $parsed_url
	 * @param  bool|boolean $domain
	 * @return string
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function unparse_url(array $parsed_url = [], bool $domain = false): string
	{
		$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
		$host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
		$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
		$user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
		$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
		$pass     = ($user || $pass) ? '$pass@' : '';
		$path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
		$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
		$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

		if ($domain == true) {
			return "$scheme$user$pass$host$port";
		} else {
			return "$scheme$user$pass$host$port$path$query$fragment";
		}
	}

/**
 * @param  string $addr
 * @return string
 */
public static function get_host(string $addr): string
{
	$parse = parse_url(trim($addr));
	$array = explode('/', $parse['path'], 2); // @phpstan-ignore-line
	return trim($parse['host'] ? $parse['host'] : array_shift($array)); // @phpstan-ignore-line
}


	/**
	 * @param  string $needle
	 * @param  array  $haystack
	 * @return array
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function array_search(string $needle, array $haystack = []): array
	{
		foreach($haystack as $key => $val) {
			if ($needle === $val) {
				return [$key];
			} elseif (is_array($val)) {
				$callback = self::array_search($needle, $val);
				if ($callback) {
					return array_merge([$key], $callback);
				}
			}
		}
		return [];
	}

	/**
	 * @param string 		$uri
	 * @param array|null $expressions
	 *
	 * @return (mixed|null|string)[]
	 *
	 * @phpstan-ignore-next-line
	 *
	 * @psalm-return non-empty-list<mixed|null|string>
	 */
	public static function parse_uri(string $uri, array $expressions = null): array
	{
		$pattern = explode('/', ltrim($uri, '/'));
		foreach ($pattern as $key => $val) {
			if (preg_match('/[\[{\(].*[\]}\)]/U', $val, $matches)) {
				foreach ($matches as $match) {
					$matchKey = substr($match, 1, -1);
					if (array_key_exists($matchKey, $expressions)) { // @phpstan-ignore-line
						$pattern[$key] = $expressions[$matchKey];
					}
				}
			}
		}
		return $pattern;
	}

	/**
	 * @return boolean
	 */
	public static function is_cli(): bool
	{
		if (defined('STDIN')) {
			return true;
		}
		if (php_sapi_name() === 'cli') {
			return true;
		}
		if (array_key_exists('SHELL', $_ENV)) {
			return true;
		}
		if (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) {
			return true;
		} 
		if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
			return true;
		}
		return false;
	}

	/**
	 * @param int    $money
	 * @param string $type
	 * @param string $locale
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function money(int $money, string $type = 'currency', string $locale = 'tr_TR')
	{
		if (extension_loaded('intl') && class_exists('NumberFormatter')) {
			$fmt = new stdClass;
			if ($type == 'decimal') {
				$fmt = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
			} elseif ($type == 'currency') {
				$fmt = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
			}
			if ($type == 'currency') {
				return trim($fmt->format($money), '₺') . '₺'; // @phpstan-ignore-line
			} else {
				return $fmt->format($money); // @phpstan-ignore-line
			}
		} else {

			if (!$money) { $money = 0; }

			// if ($locale == 'tr_TR') {
			if ($type == 'decimal') {
				return number_format($money, 2, ',', '.');
			} elseif ($type == 'currency') {
				return number_format($money, 2, ',', '.') . '₺';
			}
			// }
		}
	}

	/**
	 * @param string       $url
	 * @param array        $array
	 * @param bool|boolean $data
	 * @param bool|boolean $option
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function curl(string $url, array $array = [], bool $data = false, bool $option = false)
	{
		if ($option) {
			$domain = base64_encode(self::get_host(self::base_url())); // @phpstan-ignore-line
			$ch = curl_init($url . '&domain=' . $domain);
		} else {
			$ch = curl_init($url);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // @phpstan-ignore-line
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // @phpstan-ignore-line
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // @phpstan-ignore-line
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true); // @phpstan-ignore-line
		curl_setopt($ch, CURLOPT_TIMEOUT, 120); // @phpstan-ignore-line
		curl_setopt($ch, CURLOPT_HEADER, false); // @phpstan-ignore-line
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // @phpstan-ignore-line
		if (is_array($array)) {
			$_array = [];
			foreach ($array as $key => $val) {
				$_array[] = $key . '=' . urlencode($val);
			}

			curl_setopt($ch, CURLOPT_POST, true); // @phpstan-ignore-line
			curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_array)); // @phpstan-ignore-line
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // @phpstan-ignore-line

		$result = curl_exec($ch); // @phpstan-ignore-line
		if (curl_errno($ch) != 0 && empty($result)) { // @phpstan-ignore-line
			$result = false;
		}
		$data = ($data == true ? json_decode($result, true) : $result); // @phpstan-ignore-line
		curl_close($ch); // @phpstan-ignore-line
		return $data;
	}

	/**
	 * @param string|null  $par
	 * @param int|integer  $time
	 * @param bool|boolean $stop
	 */
	public static function redirect(string $par = null, int $time = 0, bool $stop = true)
	{
		if ($time == 0) {
			header('Location: ' . $par);
		} else {
			header('Refresh: ' . $time . '; url=' . $par);
		}
		if ($stop === true) die();
	}

	/**
	 * @param string|null  $par
	 * @param int|integer  $time
	 * @param bool|boolean $stop
	 */
	public static function refresh(string $par = null, int $time = 0, bool $stop = true)
	{
		if ($time == 0) {
			echo '<meta http-equiv="refresh" content="URL=' . $par . '">';
		} else {
			echo '<meta http-equiv="refresh" content="' . $time . ';URL=' . $par . '">';
		}
		if ($stop === true) die();
	}

	/**
	 * @param mixed        $data
	 * @param bool|boolean $stop
	 */
	public static function pr($data, bool $stop = false)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		if ($stop === true) die();
	}

	/**
	 * @param mixed        $data
	 * @param bool|boolean $stop
	 */
	public static function dump($data, bool $stop = false)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
		if ($stop === true) die();
	}

	/**
	 * @param string $class
	 * @param array $method
	 *
	 * @phpstan-ignore-next-line
	 */
	public function __call(string $class, $method)
	{
		$class = str_replace('\\Util', "", __NAMESPACE__) . '\\' . $class;
		return new $class;
	}

	/**
	 * @param string $class
	 * @param array  $method
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function __callStatic(string $class, $method)
	{
		$class = str_replace('\\Util', "", __NAMESPACE__) . '\\' . $class;
		return new $class;
	}
}