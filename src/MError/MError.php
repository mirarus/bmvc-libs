<?php

/**
 * MError
 *
 * Mirarus BMVC
 * @package BMVC\Libs\MError
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\MError;

class MError implements IMError
{

	/**
	 * @var boolean
	 */
	private static $html;

	/**
	 * @var string
	 */
	private static $title;

	/**
	 * @var boolean
	 */
	private static $stop;

	/**
	 * @var string
	 */
	private static $color;

	/**
	 * @var array
	 */
	private static $colors = [
		'danger' => '244 67 54',
		'warning' => '255 235 59',
		'info' => '3 169 244',
		'success' => '76 175 80',
		'primary' => '0 40 255',
		'dark' => '0 40 60'
	];

	/**
	 * @var array
	 */
	private static $border = [
		'top' => '60',
		'left' => '80'
	];

	public function __construct()
	{
		self::reset();
	}

	private static function reset(): void
	{
		self::$html = false;
		self::$stop = false;
		self::$title = "System Error!";
		self::$color = self::$colors['primary'];
	}

	/**
	 * @param mixed        $text
	 * @param mixed        $message
	 * @param bool|boolean $html
	 * @param string|null  $title
	 * @param string|null  $color
	 * @param bool|boolean $stop
	 * @param int|integer  $response_code
	 */
	private static function template($text, $message, bool $html = false, string $title = null, string $color = null, bool $stop = false, int $response_code = 200): void
	{
		if ($stop == true) ob_clean();
		http_response_code($response_code);
		header('Content-type: text/html;');
		echo $html == true ? '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/><title>' . $title . '</title></head><body>' : null;
		echo '<div style="padding: 15px; border-left: 5px solid rgb(' . $color . ' / ' . self::$border['left'] . '%); border-top: 5px solid rgb(' . $color . ' / ' . self::$border['top'] . '%); background: #f8f8f8; margin-bottom: 10px; border-radius: 5px 5px 0 3px;">';
		echo isset($text) && !empty($text) ? '<div style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif; font-size: 16px; font-weight: 500; color: black;">' . $text . "</div>" : null;
		echo isset($message) && !empty($message) ? '<div style="margin-top: 15px; font-size: 14px; font-family: Consolas, Monaco, Menlo, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, sans-serif; color: #ac0e10;">' . $message . "</div>" : null; 
		echo "</div>";
		echo $html == true ? "</body></html>\n" : "\n";
		if ($stop == true) die();
	}

	/**
	 * @param mixed        $text
	 * @param mixed        $message
	 * @param bool|boolean $html
	 * @param string|null  $title
	 * @param string|null  $color
	 * @param bool|boolean $stop
	 * @param int|integer  $response_code
	 */
	public static function print($text, $message = null, bool $html = false, string $title = null, string $color = null, bool $stop = false, int $response_code = 200): void
	{
		if (self::$color == null) {
			self::$color = self::$colors['primary'];
		}

		if ($color == null) {
			$color = self::$color;
		} else {
			$color = isset(self::$colors[$color]) ? self::$colors[$color] : self::$colors['primary'];
		}

		if ((self::$html == true ? self::$html : $html) == true) {
			$title = isset($title) ? $title : self::$title;
		}

		$stop = isset(self::$stop) ? self::$stop : $stop;
		self::template($text, $message, $html, $title, $color, $stop, $response_code);
		self::reset();
	}

	/**
	 * @param array $array
	 */
	public static function set(array $array): self
	{
		array_map(function ($key, $value) {
			if ($key == 'color') {
				return self::color($value);
			} if ($key == 'html') {
				return self::html($value);
			} if ($key == 'title') {
				return self::title($value);
			} if ($key == 'stop') {
				return self::stop($value);
			}
		}, array_keys($array), array_values($array));
		return new self;
	}
	
	/**
	 * @param string $color
	 */
	public static function color(string $color): self
	{
		self::$color = self::$colors[$color] ? self::$colors[$color] : self::$colors['info'];
		return new self;
	}

	/**
	 * @param bool|boolean $html
	 */
	public static function html(bool $html = false): self
	{
		self::$html = $html;
		return new self;
	}

	/**
	 * @param string $title
	 */
	public static function title(string $title): self
	{
		self::$title = $title;
		return new self;
	}

	/**
	 * @param bool|boolean $stop
	 */
	public static function stop(bool $stop = true): self
	{
		self::$stop = $stop;
		return new self;
	}
}