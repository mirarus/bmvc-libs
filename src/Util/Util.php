<?php

/**
 * Util
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Util
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Util;

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
		$url = "";

		if (isset($_GET[self::$urlGetName])) {
			$url = $_GET[self::$urlGetName];
		} elseif (isset($_SERVER['PATH_INFO'])) {
			$url = $_SERVER['PATH_INFO'];
		}

		return trim(trim($url), '/');
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
	 * @param string|integer $money
	 * @param string				 $type
	 * @param string				 $locale
	 */
	public static function money($money, string $type='currency', string $locale='tr_TR')
	{
		if (extension_loaded('intl') && class_exists("NumberFormatter")) {
			if ($type == 'decimal') {
				$fmt = new NumberFormatter($locale, NumberFormatter::DECIMAL);
			} elseif ($type == 'currency') {
				$fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);
			}
			if ($type == 'currency') {
				return trim($fmt->format($money), '₺') . '₺';
			} else {
				return $fmt->format($money);
			}
		} else {

			if (!$money) { $money = 0; }

			// if ($locale == 'tr_TR') {
			if ($type == 'decimal') {
				return number_format($money, 2, ",", ".");
			} elseif ($type == 'currency') {
				return number_format($money, 2, ",", ".") . "₺";
			}
			// }
		}
	}

	/**
	 * @param string       $url
	 * @param array        $array
	 * @param bool|boolean $data
	 * @param bool|boolean $option
	 */
	public static function curl(string $url, array $array=[], bool $data=false, bool $option=false)
	{
		if ($option) {
			$domain = base64_encode(get_host(base_url()));
			$ch = curl_init($url . "&domain=" . $domain);
		} else {
			$ch = curl_init($url);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		if (is_array($array)) {
			$_array = [];
			foreach ($array as $key => $val) {
				$_array[] = $key . '=' . urlencode($val);
			}

			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_array));
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

		$result = curl_exec($ch);
		if (curl_errno($ch) != 0 && empty($result)) {
			$result = false;
		}
		$data = ($data == true ? json_decode($result, true) : $result);
		curl_close($ch);
		return $data;
	}

	/**
	 * @param string $class
	 * @param string $method
	 */
	public function __call(string $class, string $method)
	{
		$class = str_replace('\\Util', null, __NAMESPACE__) . '\\' . $class;
		return new $class;
	}

	/**
	 * @param string $class
	 * @param array  $method
	 */
	public static function __callStatic(string $class, array $method)
	{
		$class = str_replace('\\Util', null, __NAMESPACE__) . '\\' . $class;
		return new $class;
	}
}