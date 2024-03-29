<?php

/**
 * Request
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Request
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.7
 */

namespace BMVC\Libs\Request;

use BMVC\Libs\Convert;
use BMVC\Libs\Header;
use BMVC\Libs\IP;
use BMVC\Libs\Filter;

class Request implements IRequest
{

	const METHOD_HEAD = 'HEAD';
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	const METHOD_PUT = 'PUT';
	const METHOD_PATCH = 'PATCH';
	const METHOD_DELETE = 'DELETE';
	const METHOD_OPTIONS = 'OPTIONS';
	const METHOD_OVERRIDE = '_METHOD';

	/**
	 * @var string[]
	 */
	private static $formDataMediaTypes = ['application/x-www-form-urlencoded'];

	/**
	 * @var
	 */
	public $body;

	/**
	 * @var
	 */
	public $server;

	/**
	 * @var
	 */
	public $request;

	/**
	 * @var
	 */
	public $env;

	/**
	 * @var
	 */
	public $session;

	/**
	 * @var
	 */
	public $cookie;

	/**
	 * @var
	 */
	public $files;

	/**
	 * @var
	 */
	public $post;

	/**
	 * @var
	 */
	public $get;

	public function __construct()
	{
		$this->getBody('object');
	}

	/**
	 * @param string $type
	 * @return void
	 */
	private function getBody(string $type = 'object'): void
	{
		$_body = [
			'server' => self::server(),
			'request' => self::request(),
			'env' => self::env(),
			'session' => self::session(),
			'cookie' => self::cookie(),
			'files' => self::files(),
			'post' => self::post(),
			'get' => self::get()
		];

		if ($type == 'object') {
			$this->body = Convert::arr_obj($_body);

			$this->server = $this->body->server;
			$this->request = $this->body->request;
			$this->env = $this->body->env;
			$this->session = $this->body->session;
			$this->cookie = $this->body->cookie;
			$this->files = $this->body->files;
			$this->post = $this->body->post;
			$this->get = $this->body->get;
		} elseif ($type == 'array') {
			$this->body = $_body;

			$this->server = $this->body['server'];
			$this->request = $this->body['request'];
			$this->env = $this->body['env'];
			$this->session = $this->body['session'];
			$this->cookie = $this->body['cookie'];
			$this->files = $this->body['files'];
			$this->post = $this->body['post'];
			$this->get = $this->body['get'];
		} else {
			$this->body = $_body;

			$this->server = $this->body['server'];
			$this->request = $this->body['request'];
			$this->env = $this->body['env'];
			$this->session = $this->body['session'];
			$this->cookie = $this->body['cookie'];
			$this->files = $this->body['files'];
			$this->post = $this->body['post'];
			$this->get = $this->body['get'];
		}
	}

	/**
	 * @param string|null $key
	 * @return array|mixed|null
	 */
	public static function _server(string $key = null)
	{
		if ($key) {
			return $_SERVER[$key] ?? null;
		}
		return $_SERVER;
	}

	/**
	 * @param string|null $key
	 * @param $default
	 * @return bool|mixed|mixed[]|null
	 */
	public static function header(string $key = null, $default = null)
	{
		$_header = Header::extract(self::_server());

		if ($key) {
			if ($default) {
				return $_header[$key] == $default;
			}
			return $_header[$key] ?? null;
		}
		return $_header;
	}

	/**
	 * @return string
	 */
	public static function getMethod(): string
	{
		return self::_server('REQUEST_METHOD');
	}

	/**
	 * @return string
	 */
	public static function getRequestMethod(): string
	{
		$method = self::getMethod();
		if ($method === self::METHOD_HEAD) {
			ob_start();
			$method = self::METHOD_GET;
		} elseif ($method === self::METHOD_POST) {
			$headers = [];
			if (function_exists('getallheaders')) {
				$headers = getallheaders();
			}
			foreach (self::_server() as $name => $value) {
				if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
					$headers[@strtr(ucwords(strtolower(@strtr(substr($name, 5), ['_' => ' ']))), [' ' => '-', 'Http' => 'HTTP'])] = $value;
				}
			}
			if (self::header('X-HTTP-Method-Override') !== null && in_array(self::header('X-HTTP-Method-Override'), [self::METHOD_PUT, self::METHOD_DELETE, self::METHOD_PATCH])) {
				$method = self::header('X-HTTP-Method-Override');
			}
		}
		return $method;
	}

	/**
	 * @return bool
	 */
	public static function isGet(): bool
	{
		return self::getRequestMethod() === self::METHOD_GET;
	}

	/**
	 * @return bool
	 */
	public static function isPost(): bool
	{
		return self::getRequestMethod() === self::METHOD_POST;
	}

	/**
	 * @return bool
	 */
	public static function isPut(): bool
	{
		return self::getRequestMethod() === self::METHOD_PUT;
	}

	/**
	 * @return bool
	 */
	public static function isPatch(): bool
	{
		return self::getRequestMethod() === self::METHOD_PATCH;
	}

	/**
	 * @return bool
	 */
	public static function isDelete(): bool
	{
		return self::getRequestMethod() === self::METHOD_DELETE;
	}

	/**
	 * @return bool
	 */
	public static function isHead(): bool
	{
		return self::getRequestMethod() === self::METHOD_HEAD;
	}

	/**
	 * @return bool
	 */
	public static function isOptions(): bool
	{
		return self::getRequestMethod() === self::METHOD_OPTIONS;
	}

	/**
	 * @return bool
	 */
	public static function isAjax(): bool
	{
		if (self::header('HTTP_X_REQUESTED_WITH') !== null && self::header('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest') {
			return true;
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public static function isFormData(): bool
	{
		return (self::getRequestMethod() == self::METHOD_POST && self::getContentType() == null) || in_array(self::getMediaType(), self::$formDataMediaTypes);
	}

	/**
	 * @param $content
	 *
	 * @return FormData
	 */
	public static function getFormData($content = null): FormData
	{
		return new FormData($content);
	}

	/**
	 * @return bool|mixed|mixed[]|string|null
	 */
	public static function getContentType()
	{
		return self::header('CONTENT_TYPE');
	}

	/**
	 * @return string|null
	 */
	public static function getMediaType(): ?string
	{
		$contentType = self::getContentType();
		if ($contentType) {
			$contentTypeParts = preg_split('/\s*[;,]\s*/', $contentType);
			return strtolower($contentTypeParts[0]);
		}
		return null;
	}

	/**
	 * @return array
	 */
	public static function getMediaTypeParams(): array
	{
		$contentType = self::getContentType();
		$contentTypeParams = [];
		if ($contentType) {
			$contentTypeParts = preg_split('/\s*[;,]\s*/', $contentType);
			$contentTypePartsLength = count($contentTypeParts);
			for ($i = 1; $i < $contentTypePartsLength; $i++) {
				$paramParts = explode('=', $contentTypeParts[$i]);
				$contentTypeParams[strtolower($paramParts[0])] = $paramParts[1];
			}
		}
		return $contentTypeParams;
	}

	/**
	 * @return mixed|null
	 */
	public static function getContentCharset()
	{
		$mediaTypeParams = self::getMediaTypeParams();
		if (isset($mediaTypeParams['charset'])) {
			return $mediaTypeParams['charset'];
		}
		return null;
	}

	/**
	 * @return int
	 */
	public static function getContentLength(): int
	{
		return self::header('CONTENT_LENGTH', 0);
	}

	/**
	 * @return string
	 */
	public static function getHost(): string
	{
		if (self::_server('HTTP_HOST') !== null) {
			if (strpos(self::_server('HTTP_HOST'), ':') !== false) {
				$hostParts = explode(':', self::_server('HTTP_HOST'));
				return $hostParts[0];
			}
			return self::_server('HTTP_HOST');
		}
		return self::_server('SERVER_NAME');
	}

	/**
	 * @return int
	 */
	public static function getPort(): int
	{
		return (int)self::_server('SERVER_PORT');
	}

	/**
	 * @return string
	 */
	public static function getHostWithPort(): string
	{
		return sprintf('%s:%s', self::getHost(), self::getPort());
	}

	/**
	 * @return string
	 */
	public static function getScheme(): string
	{
		return stripos(self::_server('SERVER_PROTOCOL'), 'https') ? 'https' : 'http';
	}

	/**
	 * @return string
	 */
	public static function getScriptName(): string
	{
		return self::_server('SCRIPT_NAME');
	}

	/**
	 * @return string
	 */
	public static function getPathInfo()
	{
		return self::_server('PATH_INFO');
	}

	/**
	 * @return string
	 */
	public static function getPath(): string
	{
		return self::getScriptName() . self::getPathInfo();
	}

	/**
	 * @return string
	 */
	public static function getResourceUri()
	{
		return self::getPathInfo();
	}

	/**
	 * @return string
	 */
	public static function getUrl(): string
	{
		$url = self::getScheme() . '://' . self::getHost();
		if ((self::getScheme() === 'https' && self::getPort() !== 443) || (self::getScheme() === 'http' && self::getPort() !== 80)) {
			$url .= sprintf(':%s', self::getPort());
		}
		return $url;
	}

	/**
	 * @return string
	 */
	public static function getIp(): string
	{
		return IP::get();
	}

	/**
	 * @return bool|mixed|mixed[]|string|null
	 */
	public static function getReferer()
	{
		return self::header('HTTP_REFERER');
	}

	/**
	 * @return string|null
	 */
	public static function getUserAgent()
	{
		return self::header('HTTP_USER_AGENT') ?: null;
	}

	/**
	 * @param string $domain
	 * @return bool
	 */
	public static function checkDomain(string $domain): bool
	{
		if ($domain !== trim(str_replace('www.', '', self::_server('SERVER_NAME')), '/')) {
			return false;
		}
		return true;
	}

	/**
	 * @param $ip
	 * @return bool
	 */
	public static function checkIp($ip): bool
	{
		if (isset($ip) && !empty($ip)) {
			if (is_array($ip)) {
				if (!in_array(self::getIp(), $ip)) {
					return false;
				}
			} else {
				if (self::getIp() != $ip) {
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return mixed
	 */
	public static function input(bool $db_filter = true, bool $xss_filter = true)
	{
		$data = json_decode(file_get_contents('php://input', true), true);

		if ($data && $data != null) {
			$data = $db_filter ? Filter::FilterDBM($data) : $data;
			$data = $xss_filter ? Filter::filterXSS($data) : $data;
		}

		$data = json_encode($data);

		return json_decode($data);
	}

	/**
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return mixed
	 */
	public static function _input(bool $db_filter = true, bool $xss_filter = true)
	{
		$data = json_decode(file_get_contents('php://input'), true);

		if ($data && $data != null) {
			$data = $db_filter ? Filter::FilterDBM($data) : $data;
			$data = $xss_filter ? Filter::filterXSS($data) : $data;
		}

		$data = json_encode($data);

		return json_decode($data);
	}

	/**
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return void
	 */
	public static function inputToPost(bool $db_filter = true, bool $xss_filter = true)
	{
		$_POST = Convert::obj_arr(self::_input($db_filter, $xss_filter));
	}

	/**
	 * @param string|null $data
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return array|mixed
	 */
	public static function server(string $data = null, bool $db_filter = true, bool $xss_filter = true)
	{
		return self::rea(self::_server(), $data, $db_filter, $xss_filter);
	}

	/**
	 * @param string|null $data
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return array|mixed
	 */
	public static function request(string $data = null, bool $db_filter = true, bool $xss_filter = true)
	{
		return self::rea($_REQUEST, $data, $db_filter, $xss_filter);
	}

	/**
	 * @param string|null $data
	 * @return array|mixed
	 */
	public static function env(string $data = null)
	{
		return self::rea($_ENV, $data, false, false);
	}

	/**
	 * @param string|null $data
	 * @return array|mixed
	 */
	public static function session(string $data = null)
	{
		return self::rea($_SESSION, $data, false, false);
	}

	/**
	 * @param string|null $data
	 * @return array|mixed
	 */
	public static function cookie(string $data = null)
	{
		return self::rea($_COOKIE, $data, false, false);
	}

	/**
	 * @param string|null $data
	 * @param bool $xss_filter
	 * @return array|mixed
	 */
	public static function files(string $data = null, bool $xss_filter = true)
	{
		return self::rea($_FILES, $data, false, $xss_filter);
	}

	/**
	 * @param string|null $data
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return array|mixed
	 */
	public static function post(string $data = null, bool $db_filter = true, bool $xss_filter = true)
	{
		return self::rea($_POST, $data, $db_filter, $xss_filter);
	}

	/**
	 * @param string|null $data
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return array|mixed
	 */
	public static function get(string $data = null, bool $db_filter = true, bool $xss_filter = true)
	{
		return self::rea($_GET, $data, $db_filter, $xss_filter);
	}

	/**
	 * @param string|null $data
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return array|mixed
	 */
	public static function put(string $data = null, bool $db_filter = true, bool $xss_filter = true)
	{
		return self::rea($_PUT, $data, $db_filter, $xss_filter);
	}

	/**
	 * @param string|null $data
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return array|mixed
	 */
	public static function patch(string $data = null, bool $db_filter = true, bool $xss_filter = true)
	{
		return self::rea($_PATCH, $data, $db_filter, $xss_filter);
	}

	/**
	 * @param string|null $data
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return array|mixed
	 */
	public static function delete(string $data = null, bool $db_filter = true, bool $xss_filter = true)
	{
		return self::rea($_DELETE, $data, $db_filter, $xss_filter);
	}

	/**
	 * @param string|null $data
	 * @param string $type
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return array|mixed|void
	 */
	public static function filter(string $data = null, string $type = 'post', bool $db_filter = true, bool $xss_filter = true)
	{
		if ($type == 'server') {
			return self::server($data, $db_filter, $xss_filter);
		} elseif ($type == 'request') {
			return self::request($data, $db_filter, $xss_filter);
		} elseif ($type == 'env') {
			return self::env($data);
		} elseif ($type == 'session') {
			return self::session($data);
		} elseif ($type == 'cookie') {
			return self::cookie($data);
		} elseif ($type == 'files') {
			return self::files($data, $xss_filter);
		} elseif ($type == 'post') {
			return self::post($data, $db_filter, $xss_filter);
		} elseif ($type == 'get') {
			return self::get($data, $db_filter, $xss_filter);
		} elseif ($type == 'put') {
			return self::put($data, $db_filter, $xss_filter);
		} elseif ($type == 'patch') {
			return self::patch($data, $db_filter, $xss_filter);
		} elseif ($type == 'delete') {
			return self::delete($data, $db_filter, $xss_filter);
		}
	}

	/**
	 * @param string|null $method
	 * @param string $body_type
	 * @return object
	 */
	public static function body(string $method = null, string $body_type = 'object'): object
	{
		$class = new self;

		$class->getBody($body_type);

		if ($method) {
			if ($body_type == 'object') {
				return $class->body->$method;
			} elseif ($body_type == 'array') {
				return $class->body[$method];
			} else {
				return $class->body[$method];
			}
		} else {
			return $class->body;
		}
	}

	/**
	 * @param $method
	 * @param string|null $data
	 * @param bool $db_filter
	 * @param bool $xss_filter
	 * @return array|false|mixed|string|string[]|void
	 */
	public static function rea($method, string $data = null, bool $db_filter = true, bool $xss_filter = true)
	{
		$method = $xss_filter ? Filter::filterXSS($method) : $method;

		if (isset($data) && !empty($data)) {
			if (isset($method[$data])) {
				return ($db_filter ? self::methodDB($method[$data]) : $method[$data]);
			}
		} else {
			return $db_filter ? self::methodDB($method) : $method;
		}
	}

	/**
	 * @param $method
	 * @return array|mixed|string|string[]
	 */
	private static function methodDB($method)
	{
		if (is_array($method)) {
			$md = [];
			foreach ($method as $k => $v) {
				if (count($method) > 0) {
					$md[$k] = self::methodDB($v);
				}
			}
			return $md;
		} else {
			return Filter::filterDB($method);
		}
	}
}