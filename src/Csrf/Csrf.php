<?php

/**
 * Csrf
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Csrf
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.4
 */

namespace BMVC\Libs\Csrf;

use stdClass;

class Csrf
{

  /**
   * @var string
   */
  private static $page = "b4e27faacd7a7d7ed04aecb30bd29451";

  /**
   * @param string|null $page
   * @param int $expiry
   * @return false
   */
  public static function token(string $page = null, int $expiry = 3600)
  {
    return self::getToken($page, $expiry);
  }

  /**
   * @param string|null $page
   * @param int $expiry
   * @return string|void
   */
  public static function input(string $page = null, int $expiry = 3600)
  {
    $token = self::getToken($page, $expiry);
    if ($token) {
      return '<input type="hidden" name="csrf_token" value="' . $token . '">' . "\r\n";
    }
  }

  /**
   * @param string|null $page
   * @param string|null $token
   * @return bool
   */
  public static function verify(string $page = null, string $token = null)
  {
    return self::verifyToken($page, false, $token);
  }

  /**
   * @param string|null $page
   * @return bool
   */
  public static function remove(string $page = null)
  {
    $page = $page ? $page : self::$page;
    return self::removeToken($page);
  }

  /**
   * @param string|null $page
   * @param int $expiry
   * @return false
   */
  private static function getToken(string $page = null, int $expiry = 3600)
  {
    $page = $page ? $page : self::$page;

    self::confirmSessionStarted();

    if (empty($page)) {
      return false;
    }

    $token = (self::getSessionToken($page) ? self::getSessionToken($page) : self::setNewToken($page, $expiry));

    return $token->sessiontoken;
  }

  /**
   * @param string|null $page
   * @param bool $removeToken
   * @param string|null $requestToken
   * @return bool
   */
  private static function verifyToken(string $page = null, bool $removeToken = false, string $requestToken = null): bool
  {
    $page = $page ? $page : self::$page;

    self::confirmSessionStarted();

    $requestToken = ($requestToken ? $requestToken : $_POST['csrf_token']);

    if (empty($page)) {
      return false;
    } else if (empty($requestToken)) {
      return false;
    }

    $token = self::getSessionToken($page);

    if (empty($token) || time() > (int)$token->expiry) {
      self::removeToken($page);
      return false;
    }

    $sessionConfirm = hash_equals($token->sessiontoken, $requestToken);
    $cookieConfirm = hash_equals($token->cookietoken, self::getCookieToken($page));

    if ($removeToken) {
      self::removeToken($page);
    }

    if ($sessionConfirm || $cookieConfirm) {
      return true;
    }
    return false;
  }

  /**
   * @param string $page
   * @return bool
   */
  private static function removeToken(string $page): bool
  {
    self::confirmSessionStarted();

    if (empty($page)) {
      return false;
    }

    unset($_COOKIE[self::makeCookieName($page)], $_SESSION['csrf_tokens'][$page]);

    return true;
  }

  /**
   * @param string $page
   * @param int $expiry
   * @return stdClass
   * @throws \Exception
   */
  private static function setNewToken(string $page, int $expiry): stdClass
  {
    $token = new stdClass();
    $token->page = $page;
    $token->expiry = time() + $expiry;
    $token->sessiontoken = base64_encode(random_bytes(32));
    $token->cookietoken = md5(base64_encode(random_bytes(32)));

    @setcookie(self::makeCookieName($page), $token->cookietoken, $token->expiry);

    return $_SESSION['csrf_tokens'][$page] = $token;
  }

  /**
   * @param string|null $page
   * @return mixed|null
   */
  private static function getSessionToken(string $page = null)
  {
    return !empty($_SESSION['csrf_tokens'][$page]) ? $_SESSION['csrf_tokens'][$page] : null;
  }

  /**
   * @param string $page
   * @return string
   */
  private static function getCookieToken(string $page): string
  {
    $value = self::makeCookieName($page);
    return !empty($_COOKIE[$value]) ? $_COOKIE[$value] : '';
  }

  /**
   * @param string $page
   * @return string
   */
  private static function makeCookieName(string $page): string
  {
    if (empty($page)) {
      return '';
    }
    return 'csrf_token-' . substr(md5($page), 0, 10);
  }

  /**
   * @return bool
   */
  private static function confirmSessionStarted(): bool
  {
    if (!isset($_SESSION)) {
      return false;
    }
    return true;
  }

  /**
   * @return string
   */
  public static function getPage(): string
  {
    return self::$page;
  }

  /**
   * @param string $page
   */
  public static function setPage(string $page): self
  {
    self::$page = $page;
    return new self;
  }
}