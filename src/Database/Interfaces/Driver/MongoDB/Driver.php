<?php

/**
 * MongoDB
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces\Driver\MongoDB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Interfaces\Driver\MongoDB;

interface Driver
{

	public function from(string $tableName); // @phpstan-ignore-line
	public function all(array $columns = []): array; // @phpstan-ignore-line
	public function insert(array $data = []): bool; // @phpstan-ignore-line
	public function update(array $sD = [], array $wD = []): bool; // @phpstan-ignore-line
}