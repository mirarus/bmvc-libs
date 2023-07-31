<?php

/**
 * Session
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Session
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\Session;

class Session
{

	public function __construct()
	{
		if (session_status() != PHP_SESSION_ACTIVE || session_id() == null) {
			@ini_set('session.use_only_cookies', '1');
			@session_set_cookie_params([
				'lifetime' => 3600 * 24,
				'httponly' => true,
				// 'path' => self::$url
			]);
			if ($_ENV['ENVIRONMENT'] == 'development') {
				@session_name('BMVC');
			}
			@session_start();
		}
	}

	/**
   * @param $storage
   * @param $content
   * @return void
   */
  public static function set($storage, $content = null): void
  {
    if (is_array($storage)) {
      foreach ($storage as $key => $value) {
        $_SESSION[$key] = $value;
      }
    } else {
      $_SESSION[$storage] = $content;
    }
  }

  /**
   * @param string|null $storage
   * @param string|null $child
   * @return array|mixed|void
   */
  public static function get(string $storage = null, string $child = null)
  {
    if (is_null($storage)) {
      return $_SESSION;
    }
    return self::has($storage, $child);
  }

  /**
   * @param string $storage
   * @param string|null $child
   * @return mixed|void
   */
  public static function has(string $storage, string $child = null)
  {
    if ($child === null) {
      if (isset($_SESSION[$storage])) {
        return $_SESSION[$storage];
      }
    } else {
      if (isset($_SESSION[$storage][$child])) {
        return $_SESSION[$storage][$child];
      }
    }
  }

  /**
   * @param string|null $storage
   * @param string|null $child
   * @return void
   */
  public static function delete(string $storage = null, string $child = null): void
  {
    if (is_null($storage)) {
      session_unset();
    } else {
      if ($child === null) {
        if (isset($_SESSION[$storage])) {
          unset($_SESSION[$storage]);
        }
      } else {
        if (isset($_SESSION[$storage][$child])) {
          unset($_SESSION[$storage][$child]);
        }
      }
    }
  }

  /**
   * @return void
   */
  public static function destroy(): void
  {
    session_destroy();
  }
}