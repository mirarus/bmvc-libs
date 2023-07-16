<?php

/**
 * Composer
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Composer
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Database;

use BMVC\Libs\Database\Interfaces\DriverInterface;

class Database
{

	public function __construct(DriverInterface $driver)
	{
		return $driver->connect();
	}
}