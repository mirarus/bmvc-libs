<?php

/**
 * Connect
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces\Driver\Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace Mirarus\DB\Interfaces\Driver\Mysql;

interface Connect
{

	public function __construct();
	public function __debugInfo(): array; // @phpstan-ignore-line
}