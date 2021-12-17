<?php


/**
 * Shmop
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Cache
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\Cache;

use DateTime;

use function shmop_close;
use function shmop_delete;
use function shmop_open;
use function shmop_read;
use function shmop_size;
use function shmop_write;

class Shmop
{

	/**
	 * @var array
	 *
	 * @phpstan-ignore-next-line
	 */
	private static $caches = [];

	/**
	 * @param mixed $data
	 * @param string $name
	 * @param int    $timeout
	 *
	 * @phpstan-ignore-next-line
	 *
	 * @return false|int
	 */
	public static function save_cache($data, string $name, int $timeout)
	{
		$id = shmop_open(self::get_cache_id($name), "a", 0, 0);
		shmop_delete($id); // @phpstan-ignore-line
		shmop_close($id); // @phpstan-ignore-line

		$id = shmop_open(self::get_cache_id($name), "c", 0644, strlen(serialize($data)));

		if ($id) {
			self::set_timeout($name, $timeout);
			return shmop_write($id, serialize($data), 0);
		} else {
			return false;
		}
	}

	/**
	 * @param string $name
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function get_cache(string $name)
	{
		if (!self::check_timeout($name)) {
			$id = shmop_open(self::get_cache_id($name), "a", 0, 0);

			if ($id) {
				$data = unserialize(shmop_read($id, 0, shmop_size($id)));
			} else {
				return false;
			}

			if ($data) {
				shmop_close($id);
				return $data;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * @param string $name
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function get_cache_id(string $name)
	{
		$id = self::$caches;
		return $id[$name];
	}

	/**
	 * @param string $name
	 * @param int    $int
	 *
	 * @phpstan-ignore-next-line
	 */
	private static function set_timeout(string $name, int $int): void
	{
		$timeout = new DateTime(date('Y-m-d H:i:s'));
		date_add($timeout, date_interval_create_from_date_string("$int seconds"));
		$timeout = date_format($timeout, 'YmdHis');

		$id = shmop_open(100, "a", 0, 0);
		if ($id) {
			$tl = unserialize(shmop_read($id, 0, shmop_size($id)));
		} else {
			$tl = [];
		}
		shmop_delete($id); // @phpstan-ignore-line
		shmop_close($id); // @phpstan-ignore-line

		$tl[$name] = $timeout; // @phpstan-ignore-line
		$id = shmop_open(100, "c", 0644, strlen(serialize($tl)));
		shmop_write($id, serialize($tl), 0); // @phpstan-ignore-line
	}

	/**
	 * @param string $name
	 *
	 * @phpstan-ignore-next-line
	 */
	private static function check_timeout(string $name): bool
	{
		$now = new DateTime(date('Y-m-d H:i:s'));
		$now = date_format($now, 'YmdHis');

		$id = shmop_open(100, "a", 0, 0);
		if ($id) {
			$tl = unserialize(shmop_read($id, 0, shmop_size($id)));
		} else {
			return true;
		}
		shmop_close($id);

		$timeout = $tl[$name]; // @phpstan-ignore-line
		return (intval($now) > intval($timeout));
	}
}