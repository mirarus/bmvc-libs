<?php

/**
 * Header
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 1.8
 */

namespace BMVC\Libs;

class Header
{

	private static $mime_types = [
		'binary' => 'application/octet-stream',

		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',

    // images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',

    // archives
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',

    // audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',

    // adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',

    // ms office
		'doc' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',

    // open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	];

	/**
	 * @var array
	 */
	private static $special = [
		'CONTENT_TYPE',
		'CONTENT_LENGTH',
		'PHP_AUTH_USER',
		'PHP_AUTH_PW',
		'PHP_AUTH_DIGEST',
		'AUTH_TYPE'
	];

	/**
	 * @param  array  $data
	 * @return array
	 */
	public static function extract(array $data): array
	{
		$results = [];
		foreach ($data as $key => $value) {
			$key = strtoupper($key);
			if (strpos($key, 'X_') === 0 || strpos($key, 'HTTP_') === 0 || in_array($key, self::$special)) {
				if ($key === 'HTTP_CONTENT_LENGTH') {
					continue;
				}
				$results[$key] = $value;
			}
		}
		return $results;
	}

	public static function set(): void
	{
		$args = func_get_args();

		if (is_array($args) && @$args[0]) {
			header($args[0] . ': ' . @$args[1]);
		} elseif (is_string($args)) {
			header($args);
		}
	}

	/**
	 * @param string|null $key
	 */
	public static function get(string $key=null)
	{
		$headers = array_merge(
			getallheaders(), 
			self::parse(headers_list()), 
			self::parse($http_response_header)
		);

		if ($key == null) {
			return $headers;
		} else {
			foreach ($headers as $hkey => $hval) {
				if ($hkey == $key) return trim($hval);
			}
		}
	}

	/**
	 * @param  array|null $headers
	 * @return array
	 */
	private static function parse(array $headers=null): array
	{
		$array = [];
		foreach ($headers as $header) {
			$header = explode(":", $header);
			$array[trim(array_shift($header))] = trim(implode(':', $header));
		}
		return $array;
	}

	/**
	 * @param  string|null $key
	 * @return boolean
	 */
	public static function check_type(string $key=null): bool
	{
		return (bool) (self::get('Content-type') == self::$mime_types[$key]);
	}
}