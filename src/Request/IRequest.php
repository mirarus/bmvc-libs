<?php

/**
 * IRequest
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Request
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Request;

interface IRequest
{
	
	public function __construct();
	public static function _server(string $key = null); // @phpstan-ignore-line
	public static function header(string $key = null, $default = null); // @phpstan-ignore-line
	public static function getMethod(): string;
	public static function getRequestMethod(): string;
	public static function isGet(): bool;
	public static function isPost(): bool;
	public static function isPut(): bool;
	public static function isPatch(): bool;
	public static function isDelete(): bool;
	public static function isHead(): bool;
	public static function isOptions(): bool;
	public static function isAjax(): bool;
	public static function isFormData(): bool;
	public static function getContentType(): string;
	public static function getMediaType(); // @phpstan-ignore-line
	public static function getMediaTypeParams(): array; // @phpstan-ignore-line
	public static function getContentCharset(); // @phpstan-ignore-line
	public static function getContentLength(): int;
	public static function getHost(): string;
	public static function getPort(): int;
	public static function getHostWithPort(): string;
	public static function getScheme(): string;
	public static function getScriptName(): string;
	public static function getPathInfo(): string;
	public static function getPath(): string;
	public static function getResourceUri(): string;
	public static function getUrl(): string;
	public static function getIp(): string;
	public static function getReferrer(): string;
	public static function getReferer(): string;
	public static function getUserAgent(): string;
	public static function checkDomain(string $domain): bool;
	public static function checkIp($ip): bool; // @phpstan-ignore-line
	public static function inputToPost(); // @phpstan-ignore-line
	public static function server(string $data = null, bool $db_filter = true, bool $xss_filter = true); // @phpstan-ignore-line
	public static function request(string $data = null, bool $db_filter = true, bool $xss_filter = true); // @phpstan-ignore-line
	public static function env(string $data = null); // @phpstan-ignore-line
	public static function session(string $data = null); // @phpstan-ignore-line
	public static function cookie(string $data = null); // @phpstan-ignore-line
	public static function files(string $data = null, bool $xss_filter = true); // @phpstan-ignore-line
	public static function post(string $data = null, bool $db_filter = true, bool $xss_filter = true); // @phpstan-ignore-line
	public static function get(string $data = null, bool $db_filter = true, bool $xss_filter = true); // @phpstan-ignore-line
	public static function filter(string $data = null, string $type='post', bool $db_filter = true, bool $xss_filter = true); // @phpstan-ignore-line
	public static function body(string $method = null, string $body_type='object'): object;
}