<?php

/**
 * Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces\Driver\Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Interfaces\Driver\Mysql;

interface Driver
{

  public function from(string $tableName); // @phpstan-ignore-line
  public function countFrom(string $tableName); // @phpstan-ignore-line
  public function select(string $columns); // @phpstan-ignore-line
  public function where(string $column, string $value = '', string $mark = '=', string $logical = '&&'); // @phpstan-ignore-line
  public function insert(string $tableName); // @phpstan-ignore-line
  public function update(string $tableName); // @phpstan-ignore-line
  public function delete(string $tableName); // @phpstan-ignore-line
  public function set($data, $value = null); // @phpstan-ignore-line
  public function done(); // @phpstan-ignore-line
  public function all(); // @phpstan-ignore-line
  public function first(); // @phpstan-ignore-line
}