<?php
/**
 * Csrf
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Csrf
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.5
 */

namespace BMVC\Libs\Csrf;

use stdClass;

class Csrf
{

	/**
	 * @var string
	 */
	private static $page = "b4e27faacd7a7d7ed04aecb30bd29451";
	private static $expiry = 3600;

	/**
	 * @param string|null $page
	 * @param int $expiry
	 *
	 * @return false
	 */
	public static function token(string $page = null, int $expiry = 3600)
	{
		return self::getToken($page, $expiry);
	}

	/**
	 * @param string|null $page
	 * @param int $expiry
	 *
	 * @return string|void
	 */
	public static function input(string $page = null, int $expiry = 3600)
	{
		$token = self::token($page, $expiry);
		if ($token) {
			return '<input type="hidden" name="csrf_token" value="' . $token . '">' . "\r\n";
		}
	}

	/**
	 * @param string|null $page
	 * @param int $expiry
	 *
	 * @return string|void
	 */
	public static function script(string $page = null, int $expiry = 3600)
	{
		$token = self::js($page, $expiry);
		if ($token) {
			return '<script>' . $token . '</script>';
		}
	}

	/**
	 * @param string|null $page
	 * @param int $expiry
	 *
	 * @return string|void
	 */
	public static function js(string $page = null, int $expiry = 3600)
	{
		$token = self::token($page, $expiry);
		if ($token) {
			return "var csrf_token = " . json_encode($token) . ";";
		}
	}

	/**
	 * @param string|null $page
	 * @param string|null $token
	 *
	 * @return bool
	 */
	public static function verify(string $page = null, string $token = null)
	{
		return self::verifyToken($page, $token);
	}

	/**
	 * @param string|null $page
	 *
	 * @return bool
	 */
	public static function remove(string $page = null)
	{
		$page = $page ?: self::$page;
		return self::removeToken($page);
	}

	/**
	 * @param string $page
	 *
	 * @return mixed|null
	 */
	private static function getSessionToken(string $page)
	{
		return $_SESSION['csrf_tokens'][$page] ?: null;
	}

	/**
	 * @param string $page
	 *
	 * @return string
	 */
	private static function makeCookieName(string $page): string
	{
		return $page ? 'csrf_token-' . substr(md5($page), 0, 10) : '';
	}

	/**
	 * @param string $page
	 *
	 * @return string
	 */
	private static function getCookieToken(string $page): string
	{
		$value = self::makeCookieName($page);
		return $_COOKIE[$value] ?: '';
	}


	/**
	 * @param string $page
	 *
	 * @return bool
	 */
	private static function removeToken(string $page): bool
	{
		unset($_COOKIE[self::makeCookieName($page)], $_SESSION['csrf_tokens'][$page]);
		return true;
	}

	/**
	 * @param string|null $page
	 * @param bool $removeToken
	 * @param string|null $requestToken
	 *
	 * @return bool
	 */
	private static function verifyToken(string $page = null, string $requestToken = null, bool $removeToken = false)
	{
		$page = $page ?: self::$page;

		if (isset($_POST['csrf_token'])) {
			$requestToken = $_POST['csrf_token'];
		} else if (isset($_GET['csrf_token'])) {
			$requestToken = $_GET['csrf_token'];
		}

		if (!$page || !$requestToken) {
			return false;
		}

		$token = self::getSessionToken($page);

		if ((!$token || time() > (int)$token->expiry) || $removeToken) {
			self::removeToken($page);
			return false;
		}

		$sessionConfirm = hash_equals($token->sessiontoken, $requestToken);
		$cookieConfirm = hash_equals($token->cookietoken, self::getCookieToken($page));

		if ($sessionConfirm || $cookieConfirm) {
			return true;
		}
		return false;
	}

	/**
	 * @param string $page
	 * @param int $expiry
	 *
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

		$_COOKIE['csrf_tokens'][$page] = $token;
		$_SESSION['csrf_tokens'][$page] = $token;

		return $_SESSION['csrf_tokens'][$page];
	}

	/**
	 * @param string|null $page
	 * @param int $expiry
	 *
	 * @return false
	 */
	private static function getToken(string $page = null, int $expiry = 3600)
	{
		$page = $page ?: self::$page;

		if (!$page) {
			return false;
		}

		$token = (self::getSessionToken($page) ? self::getSessionToken($page) : self::setNewToken($page, $expiry));

		return $token->sessiontoken;
	}
}