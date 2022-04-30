<?php

/**
 * MError
 *
 * Mirarus BMVC
 * @package BMVC\Libs\MError
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace BMVC\Libs\MError;

class MError implements IMError
{

  /**
   * @var bool
   */
  protected static $html = false;

  /**
   * @var bool
   */
  protected static $stop = false;

  /**
   * @var
   */
  protected static $title;

  /**
   * @var
   */
  protected static $color;

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
    self::$html = false;
    self::$stop = false;
    self::$title = "System Error!";
    self::$color = self::$colors['primary'];
  }

  /**
   * @param $text
   * @param $message
   * @param bool $html
   * @param string|null $title
   * @param string|null $color
   * @param bool $stop
   * @param int $response_code
   * @return void
   */
  private static function template($text, $message, bool $html = false, string $title = null, string $color = null, bool $stop = false, int $response_code = 200): void
  {
    if ($stop) ob_clean();
    http_response_code($response_code);
    header('Content-type: text/html;');
    echo $html ? '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/><title>' . $title . '</title></head><body>' : null;
    echo '<div style="padding: 15px; border-left: 5px solid rgb(' . $color . ' / ' . self::$border['left'] . '%); border-top: 5px solid rgb(' . $color . ' / ' . self::$border['top'] . '%); background: #f8f8f8; margin-bottom: 10px; border-radius: 5px 5px 0 3px;">';
    echo isset($text) && !empty($text) ? '<div style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, \'Helvetica Neue\', Arial, sans-serif; font-size: 16px; font-weight: 500; color: black;">' . $text . "</div>" : null;
    echo isset($message) && !empty($message) ? '<div style="margin-top: 15px; font-size: 14px; font-family: Consolas, Monaco, Menlo, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, sans-serif; color: #ac0e10;">' . $message . "</div>" : null;
    echo "</div>";
    echo $html ? "</body></html>\n" : "\n";
    if ($stop) die();
  }

  /**
   * @param $text
   * @param $message
   * @param bool $html
   * @param string|null $title
   * @param string|null $color
   * @param bool $stop
   * @param int $response_code
   * @return void
   */
  public static function print($text, $message = null, bool $html = false, string $title = null, string $color = null, bool $stop = false, int $response_code = 200): void
  {
    if (self::$color == null) {
      self::$color = self::$colors['primary'];
    }

    if ($color == null) {
      $color = self::$color;
    } else {
      $color = self::$colors[$color] ?? self::$colors['primary'];
    }

    if ((self::$html ? self::$html : $html)) {
      $title = $title ?? self::$title;
    }

    $stop = self::$stop ?: $stop;

    self::template($text, $message, $html, $title, $color, $stop, $response_code);
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
   * @param string $color
   * @return _MError
   */
  public static function color(string $color): _MError
  {
    return (new _MError)->setColor($color);
  }

  /**
   * @param bool $bool
   * @return _MError
   */
  public static function html(bool $bool = false): _MError
  {
    return (new _MError)->setHtml($bool);
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
   * @param bool $stop
   * @return _MError
   */
  public static function stop(bool $stop = true): _MError
  {
    return (new _MError)->setStop($stop);
  }
}