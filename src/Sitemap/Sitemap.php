<?php

/**
 * Sitemap
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Sitemap
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */
/**
 * Example
 * 
 * echo (new BMVC\Libs\Sitemap)
 * ->set([
 *  "loc" => "https://site.com/",
 *	"changefreq" => "daily",
 *	"priority" => "0.50",
 * ])
 * ->run();
 */

namespace BMVC\Libs\Sitemap;

use SimpleXMLElement;
use BMVC\Libs\Header;

class Sitemap
{

	private static $xml;

	private static function xml()
	{
		if (self::$xml == null) {
			self::$xml = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"/>');
		}

		if (!is_object(self::$xml)) return;
	}

	/**
	 * @param  array $data
	 * @return Sitemap
	 */
	public static function set(array $data): Sitemap
	{
		self::xml();

		$data_ = [];

		foreach ($data as $key => $val) {
			if (is_array($val)) {
				self::set($val);
			} else {
				$data_[$key] = $val;
			}
		}

		if (is_array($data_) && $data_ != null) {
			$xml = self::$xml->addChild('url');
			foreach ($data_ as $key => $val) {
				$xml->addChild($key, $val);
			}
		}

		return new self;
	}

	public static function run()
	{
		self::xml();

		Header::set('content-type', 'text/xml');

		return self::$xml->asXML();
	}
}