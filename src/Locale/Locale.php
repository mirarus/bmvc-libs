<?php

/**
 * Locale
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Locale
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Locale;

use BMVC\Libs\FS;
use BMVC\Libs\Request;
use BMVC\Libs\Response;
use BMVC\Libs\Route;
use BMVC\Libs\Util;

class Locale
{

	/**
	 * @var string
	 */
	public static $dir = 'Locales';

	/**
	 * @var null
	 */
	public static $locale;

	/**
	 * @var string
	 */
	public static $codeset = 'UTF8';

	public function __construct()
	{
		self::init();
		self::_route();
	}

	/**
	 * @return void
	 */
	public static function init(): void
	{
		if (class_exists('\Locale') && \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$_locale = \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			if ($_locale == 'tr') {
				$_locale = 'tr_TR';
			} else if ($_locale == 'en') {
				$_locale = 'en_US';
			}
		}

		if (isset($_GET['locale']) && in_array($_GET['locale'], self::list('locales'))) {
			$locale = $_GET['locale'];
			setcookie('locale', $locale, 0, '/');
			redirect(url(Util::page_url()));
		} else if (isset($_COOKIE['locale']) && in_array($_COOKIE['locale'], self::list('locales'))) {
			$locale = $_COOKIE['locale'];
		} else if (isset(self::$locale) && in_array(self::$locale, self::list('locales'))) {
			$locale = self::$locale;
		} else if (isset($_locale) && in_array($_locale, self::list('locales'))) {
			$locale = $_locale;
		} else if (isset($_ENV['LOCALE']) && in_array($_ENV['LOCALE'], self::list('locales'))) {
			$locale = $_ENV['LOCALE'];
		} else {
			$locale = 'en_US';
		}

		self::$locale = $locale;
		$lc = ($locale . '.' . self::$codeset);

		putenv("LC_ALL=" . $lc);
		putenv("LANGUAGE=" . $lc);
		putenv("LANG=" . $lc);

		if ($locale == 'tr_TR') {
			setlocale(LC_ALL, $lc, 'tr_TR', 'tr', 'turkish');
		} else {
			setlocale(LC_ALL, $lc);
		}

		bindtextdomain($locale, FS::app(self::$dir));
		bind_textdomain_codeset($locale, self::$codeset);
		textdomain($locale);
	}

	/**
	 * @param null $index
	 *
	 * @return mixed
	 */
	public static function list($index = null)
	{
		$dirLocales = FS::directories(FS::app(self::$dir));
		$codeset = mb_strtolower(self::$codeset);
		if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
			$shell = trim(shell_exec("locale -a|grep ." . $codeset));
		}
		$unixLocales = array_reduce(($shell ? explode('.' . $codeset . "\n", $shell) : []), function ($res, $el) {
			$res[] = trim(str_replace('.' . $codeset, null, $el));
			return $res;
		}, []);

		$locales = ($unixLocales ? array_intersect($dirLocales, $unixLocales) : $dirLocales);

		$arr = ($locales ? [
			'locale' => self::$locale,
			'locales' => $locales,
			'dir_locales' => $dirLocales
		] : []);

		return $index ? $arr[$index] : $arr;
	}

	private static function _route()
	{
		Route::any('locale', function () {
			$word = Request::request('word');
			$word = ($word ? _($word) : $word);
			echo Response::json($word, (!$word == null));
		});
		Route::any('locale/:all', function ($word) {
			$word = ($word ? _($word) : $word);
			echo Response::json($word, (!$word == null));
		});
	}
}