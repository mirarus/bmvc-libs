<?php

/**
 * Util
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Util
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 1.3
 */

namespace BMVC\Libs\Util;

use BMVC\Libs\Validate;

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
   * @param string|null $url
   * @param bool $atRoot
   * @param bool $atCore
   * @param bool $parse
   * @return array|int|string
   */
  public static function base_url(string $url = null, bool $atRoot = false, bool $atCore = false, bool $parse = false)
  {
    if (isset($_SERVER['HTTP_HOST'])) {
      $http = (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || $_SERVER['SERVER_PORT'] == 443 || (isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443)) ? 'https' : 'http');
      $hostname = $_SERVER['HTTP_HOST'];

      $dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
      $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', (string)realpath(__DIR__)), -1, PREG_SPLIT_NO_EMPTY);

      $core = $core[0];
      $template = $atRoot ? ($atCore ? '%s://%s/%s/' : '%s://%s/') : ($atCore ? '%s://%s/%s/' : '%s://%s%s');
      $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
      $base_url = sprintf($template, $http, $hostname, $end);
    } else {
      $base_url = 'http://localhost/';
    }

    $base_url = rtrim($base_url, '/');
    if (!empty($url)) $base_url .= $url;

    $base_url = @str_replace(trim(@$_ENV['PUBLIC_DIR'], '/'), "", rtrim($base_url, '/'));
    $base_url = trim($base_url, '/') . '/';

    if ($parse) {
      $base_url = parse_url($base_url);
      if (trim((string)self::base_url(), '/') == $base_url) $base_url['path'] = '/';
    }
    return $base_url;
  }

  /**
   * @param string|null $url
   * @param bool $print
   * @param bool $cache
   * @param bool $external
   * @return string|void
   */
  public static function url(string $url = null, bool $print = false, bool $cache = false, bool $external = false)
  {
    $external = $url && Validate::url($url);
    $burl = self::base_url();
    $_cache = ($cache ? ('?ct=' . time()) : null);
    $_url = (($url ? ($external ? $url : ($burl . $url)) : $burl) . $_cache);
    $_url = @str_replace('\\', '/', $_url);

    if ($print) {
      echo $_url;
    } else {
      return $_url;
    }
  }

  /**
   * @param array $parsed_url
   * @param bool $domain
   * @return string
   */
  public static function unparse_url(array $parsed_url = [], bool $domain = false): string
  {
    $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
    $host = $parsed_url['host'] ?: '';
    $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
    $user = $parsed_url['user'] ?: '';
    $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
    $pass = ($user || $pass) ? '$pass@' : '';
    $path = $parsed_url['path'] ?: '';
    $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

    return $domain ? "$scheme$user$pass$host$port" : "$scheme$user$pass$host$port$path$query$fragment";
  }

  /**
   * @param string $addr
   * @return string
   */
  public static function get_host(string $addr): string
  {
    $parse = parse_url(trim($addr));
    $array = explode('/', $parse['path'], 2);
    return trim($parse['host'] ? $parse['host'] : array_shift($array));
  }

	/**
	 * @param string $addr
	 * @return string
	 */
	public static function getHost(string $addr): string
	{
		$parsed_url = parse_url(trim($addr));
		$scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
		$host = $parsed_url['host'] ?: '';
		$port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
		$user = $parsed_url['user'] ?: '';
		$pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
		$pass = ($user || $pass) ? '$pass@' : '';
		return "$scheme$user$pass$host$port";
	}

  /**
   * @param string $needle
   * @param array $haystack
   * @return array|int|string
   */
  public static function array_search(string $needle, array $haystack = [])
  {
    foreach ($haystack as $key => $val) {
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
   * @param string $uri
   * @param array|null $expressions
   * @return array
   */
  public static function parse_uri(string $uri, array $expressions = null): array
  {
    $pattern = explode('/', ltrim($uri, '/'));
    foreach ($pattern as $key => $val) {
      if (preg_match('/[\[{\(].*[\]}\)]/U', $val, $matches)) {
        foreach ($matches as $match) {
          $matchKey = substr($match, 1, -1);
          if ($expressions && array_key_exists($matchKey, $expressions)) {
            $pattern[$key] = $expressions[$matchKey];
          }
        }
      }
    }
    return $pattern;
  }

  /**
   * @return bool
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
   * @param $money
   * @param string $type
   * @param string $locale
   * @return false|string
   */
  public static function money($money, string $type = 'decimal', string $locale = 'en-US')
  {
    if (extension_loaded('intl') && class_exists('NumberFormatter')) {
      $fmt = new \NumberFormatter($locale, (($type == 'decimal') ? \NumberFormatter::DECIMAL : \NumberFormatter::CURRENCY));
      return $fmt->format($money);
    } else {
      if (!$money) $money = 0.0;

      if ($locale == 'tr_TR') {
        return number_format($money, 2, ',', '.');
      } elseif ($locale == 'en-US') {
        return number_format($money, 2, '.', '');
      } else {
        return number_format($money, 2, ',', '.');
      }
    }
  }

  /**
   * @param string $url
   * @param array $array
   * @param bool $data
   * @param bool $option
   * @return bool|mixed|string
   */
  public static function curl(string $url, array $array = [], bool $data = false, bool $option = false)
  {
    if ($option) {
      $domain = base64_encode(self::get_host(self::base_url()));
      $ch = curl_init($url . '&domain=' . $domain);
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
    $data = ($data ? json_decode($result, true) : $result);
    curl_close($ch);
    return $data;
  }

  /**
   * @param string|null $par
   * @param int $time
   * @param bool $stop
   * @return void
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
   * @param string|null $par
   * @param int $time
   * @param bool $stop
   * @return void
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
   * @param $data
   * @param bool $stop
   * @return void
   */
  public static function pr($data, bool $stop = false)
  {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    if ($stop === true) die();
  }

  /**
   * @param $data
   * @param bool $stop
   * @return void
   */
  public static function dump($data, bool $stop = false)
  {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    if ($stop === true) die();
  }

  /**
   * @param string $date
   * @param string $format
   * @return false|int
   */
  public static function date_to_time(string $date, string $format = 'YYYY-MM-DD')
  {
    $year = 0;
    $month = 0;
    $day = 0;

    if ($format == 'YYYY-MM-DD') list($year, $month, $day) = explode('-', $date);
    if ($format == 'YYYY/MM/DD') list($year, $month, $day) = explode('/', $date);
    if ($format == 'YYYY.MM.DD') list($year, $month, $day) = explode('.', $date);

    if ($format == 'DD-MM-YYYY') list($day, $month, $year) = explode('-', $date);
    if ($format == 'DD/MM/YYYY') list($day, $month, $year) = explode('/', $date);
    if ($format == 'DD.MM.YYYY') list($day, $month, $year) = explode('.', $date);

    if ($format == 'MM-DD-YYYY') list($month, $day, $year) = explode('-', $date);
    if ($format == 'MM/DD/YYYY') list($month, $day, $year) = explode('/', $date);
    if ($format == 'MM.DD.YYYY') list($month, $day, $year) = explode('.', $date);

    return mktime(0, 0, 0, $month, $day, $year);
  }

  /**
   * @param string $file
   * @param int $w
   * @param int $h
   * @param bool $crop
   * @param string|null $savePath
   * @return null|resource
   */
  public static function image_resize(string $file, int $w, int $h, bool $crop = false, string $savePath = null)
  {
    list($width, $height) = getimagesize($file);

    $r = $width / $height;

    if ($crop) {

      if ($width > $height) {
        $width = ceil($width - ($width * abs($r - $w / $h)));
      } else {
        $height = ceil($height - ($height * abs($r - $w / $h)));
      }
      $newWidth = $w;
      $newHeight = $h;

    } else {
      if ($w / $h > $r) {
        $newWidth = $h * $r;
        $newHeight = $h;
      } else {
        $newWidth = $w;
        $newHeight = $w / $r;
      }
    }

    if (exif_imagetype($file) == 1) {
      $src = imagecreatefromgif($file);
    } elseif (exif_imagetype($file) == 2) {
      $src = imagecreatefromjpeg($file);
    } elseif (exif_imagetype($file) == 3) {
      $src = imagecreatefrompng($file);
    } elseif (exif_imagetype($file) == 6) {
      $src = imagecreatefrombmp($file);
    } elseif (exif_imagetype($file) == 15) {
      $src = imagecreatefromwbmp($file);
    } elseif (exif_imagetype($file) == 16) {
      $src = imagecreatefromxbm($file);
    } elseif (exif_imagetype($file) == 18) {
      $src = imagecreatefromwebp($file);
    } else {
      $src = imagecreatefromjpeg($file);
    }

    $dst = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if ($savePath) {
      if (exif_imagetype($file) == 1) {
        $dst = imagegif($dst, $savePath);
      } elseif (exif_imagetype($file) == 2) {
        $dst = imagejpeg($dst, $savePath, 100);
      } elseif (exif_imagetype($file) == 3) {
        $dst = imagepng($dst, $savePath, 100);
      } elseif (exif_imagetype($file) == 6) {
        $dst = imagebmp($dst, $savePath);
      } elseif (exif_imagetype($file) == 15) {
        $dst = imagewbmp($dst, $savePath);
      } elseif (exif_imagetype($file) == 16) {
        $dst = imagexbm($dst, $savePath);
      } elseif (exif_imagetype($file) == 18) {
        $dst = imagewebp($dst, $savePath, 100);
      } else {
        $dst = imagejpeg($dst, $savePath, 100);
      }
    }

    return $dst;
  }

  /**
   * @param string $class
   * @param $method
   * @return mixed
   */
  public function __call(string $class, $method)
  {
    $class = str_replace('\\Util', "", __NAMESPACE__) . '\\' . $class;
    return new $class;
  }

  /**
   * @param string $class
   * @param $method
   * @return mixed
   */
  public static function __callStatic(string $class, $method)
  {
    $class = str_replace('\\Util', "", __NAMESPACE__) . '\\' . $class;
    return new $class;
  }
}