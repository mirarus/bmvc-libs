<?php

/**
 * Util
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs;

final class Util
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
		$url = "";

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
	public static function get_method(): string
	{
		return filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_ENCODED);
	}

	/**
	 * @return string
	 */
	public static function get_ip(): string
	{
		if (getenv("HTTP_CLIENT_IP")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} elseif (getenv("HTTP_X_FORWARDED_FOR")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
			if (strstr($ip, ',')) {
				$tmp = explode (',', $ip);
				$ip = trim($tmp[0]);
			}
		} else {
			$ip = getenv("REMOTE_ADDR");
		}

		if (isset($ip)) return $ip;

		$keys = ['X_FORWARDED_FOR', 'HTTP_X_FORWARDED_FOR', 'CLIENT_IP', 'REMOTE_ADDR'];
		foreach ($keys as $key) {
			$result = $_SERVER[$key] ?? null;
		}

		if (isset($result)) return $result;

		return "";
	}

	/**
	 * @param  string $needle
	 * @param  array  $haystack
	 * @return array
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
	 * @param  string $uri
	 * @param  array  $expressions
	 * @return array
	 */
	public static function parse_uri(string $uri, array $expressions = []): array
	{
		$pattern = explode('/', ltrim($uri, '/'));
		foreach ($pattern as $key => $val) {
			if (preg_match('/[\[{\(].*[\]}\)]/U', $val, $matches)) {
				foreach ($matches as $match) {
					$matchKey = substr($match, 1, -1);
					if (array_key_exists($matchKey, $expressions))
						$pattern[$key] = $expressions[$matchKey];
				}
			}
		}
		return $pattern;
	}
}