<?php

/**
 * BasicDB_Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Interfaces\Driver\BasicDB_Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace Mirarus\DB\Interfaces\Driver\BasicDB_Mysql;

use Closure;

interface Driver
{

  public function from(string $tableName); // @phpstan-ignore-line
  public function Countfrom(string $tableName); // @phpstan-ignore-line
  public function select(string $columns); // @phpstan-ignore-line
  public function union(); // @phpstan-ignore-line
  public function group(Closure $fn); // @phpstan-ignore-line
  public function where($column, string $value = '', string $mark = '=', string $logical = '&&'); // @phpstan-ignore-line
  public function having($column, string $value = '', string $mark = '=', string $logical = '&&'); // @phpstan-ignore-line
  public function or_where($column, string $value, string $mark = '='); // @phpstan-ignore-line
  public function or_having($column, string $value, string $mark = '='); // @phpstan-ignore-line
  public function join(string $targetTable, $joinSql, string $joinType = 'inner'); // @phpstan-ignore-line
  public function leftJoin(string $targetTable, $joinSql); // @phpstan-ignore-line
  public function rightJoin(string $targetTable, $joinSql); // @phpstan-ignore-line
  public function orderBy(string $columnName, string $sort = 'ASC'); // @phpstan-ignore-line
  public function groupBy(string $columnName); // @phpstan-ignore-line
  public function limit($start, $limit); // @phpstan-ignore-line
  public function sql(string $sqlq); // @phpstan-ignore-line
  public function all(); // @phpstan-ignore-line
  public function first(); // @phpstan-ignore-line
  public function rowCount(); // @phpstan-ignore-line
  public function fetchCol(); // @phpstan-ignore-line
  public function generateQuery(); // @phpstan-ignore-line
  public function insert(string $tableName); // @phpstan-ignore-line
  public function set($data, $value = null); // @phpstan-ignore-line
  public function lastId(); // @phpstan-ignore-line
  public function lastId2($data); // @phpstan-ignore-line
  public function lastId3(); // @phpstan-ignore-line
  public function update(string $tableName); // @phpstan-ignore-line
  public function delete(string $tableName); // @phpstan-ignore-line
  public function done(); // @phpstan-ignore-line
  public function total(); // @phpstan-ignore-line
  public function pagination($totalRecord, $paginationLimit, $pageParamName); // @phpstan-ignore-line
  public function showPagination($url, string $class = 'active'); // @phpstan-ignore-line
  public function nextPage(); // @phpstan-ignore-line
  public function prevPage(); // @phpstan-ignore-line
  public function getSqlString(); // @phpstan-ignore-line
  public function between($column, $values); // @phpstan-ignore-line
  public function notBetween($column, $values); // @phpstan-ignore-line
  public function findInSet($column, $value); // @phpstan-ignore-line
  public function findInSetReverse($column, $value); // @phpstan-ignore-line
  public function in($column, $value); // @phpstan-ignore-line
  public function notIn($column, $value); // @phpstan-ignore-line
  public function like($column, $value, string $inner = 'all'); // @phpstan-ignore-line
  public function notLike($column, $value, string $inner = 'all'); // @phpstan-ignore-line
  public function soundex($column, $value); // @phpstan-ignore-line
  public function truncate(string $tableName); // @phpstan-ignore-line
  public function truncateAll(array $dbs = []); // @phpstan-ignore-line
  public function setAutoIncrement(string $tableName, int $ai = 1); // @phpstan-ignore-line
  public function __call(string $name, $args); // @phpstan-ignore-line
}