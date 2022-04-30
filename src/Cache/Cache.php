<?php

/**
 * Cache
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Cache
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\Cache;

use Exception;
use BMVC\Libs\FS;

class Cache
{

  /**
   * @var string
   */
  private static $path;

  /**
   * @var string
   */
  private static $filename;

  /**
   * @var string
   */
  private static $extension;

  /**
   * @var int
   */
  private static $expire;

  public function __construct()
  {
    self::$path = FS::app('Cache');
    self::$filename = 'default-cache';
    self::$extension = '.cache';
    self::$expire = 604800;
  }

  /**
   * @param string $key
   * @param $data
   * @param int|null $expiration
   * @return void
   */
  public static function save(string $key, $data, int $expiration = null): void
  {
    if (is_null($expiration)) {
      $expiration = self::$expire;
    }
    $storedData = [
      'time' => time(),
      'expire' => $expiration,
      'data' => serialize($data)
    ];
    $content = self::loadCache();
    if (is_array($content) === true) {
      $content[$key] = $storedData;
    } else {
      $content = [$key => $storedData];
    }
    $content = json_encode($content);
    file_put_contents(self::getCacheDir(), $content);
  }

  /**
   * @param string $key
   * @param string|null $filename
   * @return mixed|null
   */
  public static function read(string $key, string $filename = null)
  {
    $content = self::loadCache($filename);
    if (!isset($content[$key]['data'])) {
      return null;
    } else {
      return unserialize($content[$key]['data']);
    }
  }

  /**
   * @param string $key
   * @return void
   * @throws Exception
   */
  public static function delete(string $key): void
  {
    $content = self::loadCache();
    if (is_array($content)) {
      if (isset($content[$key])) {
        unset($content[$key]);
        $content = json_encode($content);
        file_put_contents(self::getCacheDir(), $content);
      } else {
        throw new Exception('Cache Error! | delete() - Key ' . $key . ' not found.');
      }
    }
  }

  /**
   * @return int
   */
  public static function deleteExpiredCache()
  {
    $count = 0;
    $content = self::loadCache();
    if (is_array($content)) {
      foreach ($content as $key => $value) {
        if (self::isExpired($value['time'], $value['expire']) === true) {
          unset($content[$key]);
          $count++;
        }
      }
      if ($count > 0) {
        $content = json_encode($content);
        file_put_contents(self::getCacheDir(), $content);
      }
    }
    return $count;
  }

  /**
   * @return bool
   */
  public static function clear(): bool
  {
    if (FS::is_file(self::getCacheDir())) {
      $file = fopen(self::getCacheDir(), 'w');
      fclose($file);
      return true;
    }
    return false;
  }

  /**
   * @param string $key
   * @return bool
   */
  public static function isCached(string $key): bool
  {
    self::deleteExpiredCache();
    if (self::loadCache()) {
      $cacheContent = self::loadCache();
      return isset($cacheContent[$key]['data']);
    }
    return false;
  }

  /**
   * @param string $filename
   * @return void
   */
  public static function setFileName(string $filename): void
  {
    self::$filename = $filename;
  }

  /**
   * @return string
   */
  public static function getFileName(): string
  {
    return self::$filename;
  }

  /**
   * @param string $path
   * @return void
   */
  public static function setPath(string $path): void
  {
    self::$path = $path;
  }

  /**
   * @return string
   */
  public static function getPath(): string
  {
    return self::$path;
  }

  /**
   * @param string $extension
   * @return void
   */
  public static function setExtension(string $extension): void
  {
    self::$extension = $extension;
  }

  /**
   * @return string
   */
  public static function getExtension(): string
  {
    return self::$extension;
  }

  /**
   * @return bool|void
   * @throws Exception
   */
  private static function checkCacheDir()
  {
    if (!is_dir(self::getPath()) && !mkdir(self::getPath(), 0775, true)) {
      throw new Exception('Cache Error! | Failed to create cache directory. - ' . self::getPath());
    } elseif (!is_readable(self::getPath()) || !is_writable(self::getPath())) {
      if (!chmod(self::getPath(), 0775)) {
        throw new Exception('Cache Error! | ' . self::getPath() . ' must have read and write permissions to the directory.');
      }
    } else {
      return true;
    }
  }

  /**
   * @param string|null $filename
   * @return false|string
   * @throws Exception
   */
  private static function getCacheDir(string $filename = null)
  {
    if (self::checkCacheDir() !== true) {
      return false;
    }
    if (is_null($filename)) {
      $filename = preg_replace('/[^0-9a-z\.\_\-]/i', '', strtolower(self::getFileName()));
    }
    return self::getPath() . '/' . md5($filename) . self::getExtension();
  }

  /**
   * @param string|null $filename
   * @return false|mixed
   * @throws Exception
   */
  private static function loadCache(string $filename = null)
  {
    if (self::getCacheDir() === false) {
      return false;
    }
    if (!file_exists(self::getCacheDir($filename))) {
      return false;
    }
    $file = file_get_contents(self::getCacheDir($filename));
    return json_decode($file, true);
  }

  /**
   * @param int $time
   * @param int $expiration
   * @return bool
   */
  private static function isExpired(int $time, int $expiration): bool
  {
    if ($expiration === 0) {
      return false;
    }
    return time() - $time > $expiration;
  }
}