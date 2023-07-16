<?php

/**
 * Connect
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Driver\Mysql;

use Mirarus\DB\DB;
use Mirarus\DB\Connect as _Connect;
use Mirarus\DB\Interfaces\Driver\Mysql\Connect as IConnect;
use PDO;
use PDOException;

class Connect implements IConnect
{

	protected $conn; // @phpstan-ignore-line
	protected $error; // @phpstan-ignore-line

	public function __construct()
	{
		try {
			$this->conn = new PDO(..._Connect::get('dsn'));
			$this->conn->query('SET CHARACTER SET utf8');
			$this->conn->query('SET NAMES utf8 utf8');
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		} catch (PDOException $e) {
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