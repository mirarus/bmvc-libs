<?php

/**
 * Lang
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 6.8
 */

namespace BMVC\Libs;

use Exception;

class Lang
{

	/**
	 * @var string
	 */
	public static $dir = 'Languages';

	/**
	 * @var array
	 */
	private static $langs = [];

	/**
	 * @var string
	 */
	public static $lang = 'en';

	/**
	 * @var string
	 */
	private static $current_lang = 'en';

	public function __construct()
	{
		self::$dir = FS::app(self::$dir);
		FS::mk_dir(self::$dir);

		$_lang = $_ENV['LANG'];
		
		if ($_lang != null) {

			if (is_array($_lang)) {

				$func = array_shift($_lang);
				$lang = call_user_func_array($func, $_lang);

				if ($lang) {
					self::$current_lang = self::$lang = $lang;
				}
			} else {
				self::$current_lang = self::$lang = $_lang;
			}
		}

		self::$langs = self::_get_langs();
		self::$current_lang = self::get();

		self::_routes();
	}

	/**
	 * @param  string $lang
	 * @return array
	 */
	public static function get_lang(string $lang): array
	{
		$info = self::_get_lang_info($lang);
		$current = self::$current_lang == $lang ? true : false;
		$name = $current ? $info['name-local'] : $info['name-global'];
		$url = url('lang/set/' . $info['code']);

		if ($info == null) return [];

		return [
			'info' => $info,
			'name' => $name,
			'url' => $url,
			'current' => $current
		];
	}

	/**
	 * @return array
	 */
	public static function get_langs(): array
	{
		$_langs = [];
		foreach (self::$langs as $lang) {
			$_langs[$lang] = self::get_lang($lang);
		}
		return $_langs;
	}

	/**
	 * @return string
	 */
	public static function get(): string
	{
		if (isset($_SESSION[md5('language')])) {
			return $_SESSION[md5('language')];
		}
		$_SESSION[md5('language')] = self::$lang;
		return self::$lang;
	}

	/**
	 * @param string|null $lang
	 */
	public static function set(string $lang=null): void
	{				
		if (empty($lang)) {
			$lang = self::$current_lang;
		} if (in_array($lang, self::$langs)) {
			$_SESSION[md5('language')] = $lang;
		}
	}

	/**
	 * @param string $text
	 * @param mixed $replace
	 */
	public static function __(string $text, $replace=null)
	{
		self::_init($text, false, $replace);
	}

	/**
	 * @param string $text
	 * @param mixed $replace
	 */
	public static function ___(string $text, $replace=null)
	{
		return self::_init($text, true, $replace);
	}

	/**
	 * @param string       $text
	 * @param bool|boolean $return
	 * @param mixed  	     $replace
	 */
	private static function _init(string $text, bool $return=true, $replace=null)
	{
		if ($return == true) {
			if ($replace != null) {
				if (is_array($replace)) {
					return vsprintf(self::_get_text($text), $replace);
				} else {
					return sprintf(self::_get_text($text), $replace);
				}
			} else {
				return self::_get_text($text);
			}
		} else {
			if ($replace != null) {
				if (is_array($replace)) {
					vprintf(self::_get_text($text), $replace);
				} else {
					printf(self::_get_text($text), $replace);
				}
			} else {
				echo self::_get_text($text);
			}
		}
	}

	/**
	 * @param string $text
	 */
	private static function _get_text(string $text)
	{
		if (self::$current_lang == 'index') return;

		$_config = false;

		if ($file = self::_config_file()) {

			$_config = true;
			$_lang = $file[self::$current_lang];

			if (isset($_lang)) {
				$_lang = $_lang['langs'];
				if (isset($_lang[$text])) {
					return $_lang[$text];
				} else {
					return $text;
				}
			} else {
				throw new Exception('Language Not Found! | Language: ' . self::$current_lang);
			}
		}

		if ($_config == false) {
			if (file_exists($file = FS::implode([self::$dir, self::$current_lang . '.php']))) {

				$_lang = [];
				include $file;
				if (isset($_lang[$text])) {
					return $_lang[$text];
				} else {
					$text = ucfirst(str_replace(['-', '_'], ' ', $text));
					return $text;
				}
			} else {
				throw new Exception('Language Not Found! | Language: ' . self::$current_lang);
			}
		}
	}

	private static function _get_langs()
	{
		$_config = false;

		if ($file = self::_config_file()) {

			$_config = true;

			if (array_keys($file) != 'index') return array_keys($file);
		}

		if ($_config == false) {

			$files = [];
			foreach (glob(FS::implode([self::$dir, '*.php'])) as $file) {
				if ($file != FS::implode([self::$dir, 'index.php'])) {

					$_lang = [];
					include $file;
					if ($_lang != null) {
						$files[] = FS::trim(str_replace([self::$dir, '.php'], '', $file));
					}
				}
			}
			return $files;
		}
	}

	/**
	 * @param string      $_xlang
	 * @param string|null $par
	 */
	private static function _get_lang_info(string $_xlang, string $par=null)
	{
		if ($_xlang == 'index') return;

		$_config = false;
		$_data = [];
		$_lang = [];

		if ($file = self::_config_file()) {

			$_lang_ = $file[$_xlang];

			if (isset($_lang_) && isset($_lang_['info'])) {

				$_config = true;

				$_lang = $_lang_['langs'];

				$_data = [
					'code' => @$_xlang,
					'name-global' => @$_lang_['info']['name-global'],
					'name-local' => @$_lang_['info']['name-local']
				];
			} else {
				throw new Exception('Language Not Found! | Language: ' . $_xlang);
			}
		}

		if ($_config == false) {
			if (file_exists($file = FS::implode([self::$dir, $_xlang . '.php']))) {

				include $file;

				$_data = [
					'code' => @$_xlang,
					'name-global' => @$_lang_name[0],
					'name-local' => @$_lang_name[1]
				];
			} else {
				throw new Exception('Language Not Found! | Language: ' . $_xlang);
			}
		}

		if (@$_lang != null && @$_data['code'] != null && @$_data['name-global'] != null && @$_data['name-local'] != null) {
			if ($par != null) {
				return $_data[$par];
			} else {
				return $_data;
			}
		}
	}

	/**
	 * @param array|null &$_file
	 */
	private static function _config_file(array &$_file=null)
	{
		if (file_exists($file = FS::implode([self::$dir, 'config.php']))) {

			$_file_ = include ($file);

			if (is_array($_file_) && !empty($_file_)) {
				return $_file = $_file_;
			}
		}
	}

	private static function _routes(): void
	{
		if (CL::is_class(\BMVC\Core\Route::class)) {

			\BMVC\Core\Route::prefix('lang')::group(function() {

				\BMVC\Core\Route::match(['GET', 'POST'], 'set/{lowercase}', function($lang) {
					self::set($lang);
					if (Request::isGet()) {
						redirect(url());
					}
				});

				\BMVC\Core\Route::match(['GET', 'POST'], 'get/{all}', function($url) {
					$par  = explode('/', $url);
					$text = array_shift($par);

					if (isset($par[0]) && $par[0] == "true") {
						self::___($text, Request::request('replace'));
					} else {
						self::__($text, Request::request('replace'));
					}
				});
			});
		}
	}
}