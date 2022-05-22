<?php

/**
 * MError
 *
 * Mirarus BMVC
 * @package BMVC\Libs\MError
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.4
 */

namespace BMVC\Libs\MError;

class MError implements IMError
{

  /**
   * @var
   */
  protected static $html;

  /**
   * @var
   */
  protected static $stop;

  /**
   * @var
   */
  protected static $title;

  /**
   * @var
   */
  protected static $color;

  /**
   * @var
   */
  protected static $code;

  /**
   * @var string[]
   */
  protected static $colors = [
    'danger' => '244 67 54',
    'warning' => '255 235 59',
    'info' => '3 169 244',
    'success' => '76 175 80',
    'primary' => '0 40 255',
    'dark' => '0 40 60'
  ];

  /**
   * @var string[]
   */
  private static $border = [
    'top' => '60',
    'left' => '80'
  ];

  public function __construct()
  {
    self::reset();
  }

  /**
   * @return void
   */
  private static function reset(): void
  {
    self::$html = null;
    self::$stop = null;
    self::$title = null;
    self::$color = null;
    self::$code = null;
  }

  /**
   * @param string|null $title
   * @param $text
   * @param $message
   * @param bool $html
   * @param bool $stop
   * @param string|null $color
   * @param int $code
   * @return void
   */
  private static function template(string $title = null, $text, $message, bool $html = false, bool $stop = false, string $color = null, int $code = 200): void
  {
    if ($stop) ob_clean();
    http_response_code($code);
    header('Content-type: text/html;');
    echo $html ? '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/><meta name="response" content="' . $code . '"/><title>' . $title . '</title></head><body>' : null;
    echo '<div style="padding: 15px; border-left: 5px solid rgb(' . $color . ' / ' . self::$border['left'] . '%); border-top: 5px solid rgb(' . $color . ' / ' . self::$border['top'] . '%); background: #f8f8f8; margin-bottom: 10px; border-radius: 5px 5px 0 3px;">';
    echo $text ? '<div style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif; font-size: 16px; font-weight: 500; color: black;">' . $text . "</div>" : null;
    echo $message ? '<div style="margin-top: 15px; font-size: 14px; font-family: Consolas, Monaco, Menlo, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, sans-serif; color: #ac0e10;">' . $message . "</div>" : null;
    echo $html ? "</div></body></html>" : "</div>\n";
    echo ($html && $stop) ? null : "\n";
    if ($stop) die();
  }

  /**
   * @param $text
   * @param $message
   * @param bool $html
   * @param string|null $title
   * @param string|null $color
   * @param bool $stop
   * @param int $code
   * @return void
   */
  public static function print($text, $message = null, bool $html = false, string $title = null, string $color = null, bool $stop = false, int $code = 200): void
  {
    $title = self::$title ? self::$title : ($title ? $title : "System Error!");
    $html = self::$html ? self::$html : $html;
    $stop = self::$stop ? self::$stop : $stop;
    $color = self::$colors[$color] ? self::$colors[$color] : self::$colors['primary'];
    $code = self::$code ? self::$code : $code;

    self::template($title, $text, $message, $html, $stop, $color, $code);
    self::reset();
  }

  /**
   * @param string|null $title
   * @param $text
   * @param $message
   * @param bool $html
   * @param bool $stop
   * @param string|null $color
   * @param int $code
   * @return void
   */
  public static function p(string $title = null, $text, $message = null, bool $html = false, bool $stop = false, string $color = null, int $code = 200): void
  {
    self::print($text, $message, $html, $title, $color, $stop, $code);
    self::reset();
  }

  /**
   * @param array $array
   * @return _MError
   */
  public static function set(array $array): _MError
  {
    return (new _MError)->setData($array);
  }

  /**
   * @param bool $html
   * @return _MError
   */
  public static function html(bool $html = true): _MError
  {
    return (new _MError)->setHtml($html);
  }

  /**
   * @param bool $stop
   * @return _MError
   */
  public static function stop(bool $stop = true): _MError
  {
    return (new _MError)->setStop($stop);
  }

  /**
   * @param string $title
   * @return _MError
   */
  public static function title(string $title): _MError
  {
    return (new _MError)->setTitle($title);
  }

  /**
   * @param string $color
   * @return _MError
   */
  public static function color(string $color): _MError
  {
    return (new _MError)->setColor($color);
  }

  /**
   * @param int $code
   * @return _MError
   */
  public static function code(int $code = 200): _MError
  {
    return (new _MError)->setCode($code);
  }
}