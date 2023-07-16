<?php

/**
 * Driver
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.4
 */

namespace BMVC\Libs\Database;

class Driver
{

	protected $dsn;
	private $time = 0.0;
	private $times = [];

	public function setDsn(...$dsn)
	{
		$this->dsn = is_array($dsn[0]) ? $dsn[0] : $dsn;
	}

	protected function getDsn()
	{
		return $this->dsn;
	}

	protected function setTime($time, string $method)
	{
		[$class, $method] = explode('::', $method);
		$this->times[$class][$method] = ($time - $this->time);
	}

	public function getTime(string $class = null)
	{
		return $class ? $this->times[$class] : $this->times;
	}
}