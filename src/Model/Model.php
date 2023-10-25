<?php

/**
 * Model
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Model
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Model;

use BMVC\Libs\classCall;
use Mirarus\DB\Connect;
use Mirarus\DB\DB;

class Model
{

	use classCall;

	/**
	 * @var array
	 */
	private static $config = [];

	/**
	 * @var array
	 */
	private static $connect = [];

	public function __construct()
	{
		self::DB();
	}

	/**
	 * @param array $arr
	 * @return void
	 */
	public static function config(array $arr): void
	{
		self::$config = $arr;
		self::$connect = array_key_exists('connect', $arr) ? $arr['connect'] : self::$connect;
	}

	/**
	 * @return DB|void
	 */
	public static function DB()
	{
		$connect = new Connect();
		$connect->driver('basicdb-mysql');
		$connect->dsn(self::$connect['dsn'], self::$connect['username'], self::$connect['password']);
		return new DB($connect);
	}
}