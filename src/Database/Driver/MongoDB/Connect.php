<?php

/**
 * Connect
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\MongoDB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Driver\MongoDB;

use Mirarus\DB\DB;
use Mirarus\DB\Connect as _Connect;
use Mirarus\DB\Interfaces\Driver\MongoDB\Connect as IConnect;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Exception\ConnectionException;

class Connect implements IConnect
{

	protected $dbName; // @phpstan-ignore-line
	protected $conn; // @phpstan-ignore-line
	protected $error; // @phpstan-ignore-line

	public function __construct()
	{
		$dsn = _Connect::get('dsn');
		try {
			$this->dbName = array_pop($dsn);
			$this->conn = new Manager(...$dsn);
		} catch (ConnectionException $e) {
			$this->error = $e;
		}

		DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
	}

	/**
	 * @return array
	 */
	public function __debugInfo(): array // @phpstan-ignore-line
	{
		return [
			'_connect' => $this->conn,
			'_error' => $this->error
		];
	}
}