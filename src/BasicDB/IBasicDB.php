<?php

/**
 * IBasicDB
 *
 * Mirarus BMVC
 * @package BMVC\Libs\BasicDB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Benchmark;

interface IBasicDB
{

	public function from($tableName);
	public function Countfrom($tableName);
  public function select($columns);
  public function union();
  public function group(Closure $fn);
  public function where($column, $value = '', $mark = '=', $logical = '&&');
  public function having($column, $value = '', $mark = '=', $logical = '&&');
  public function or_where($column, $value, $mark = '=');
  public function or_having($column, $value, $mark = '=');
  public function join($targetTable, $joinSql, $joinType = 'inner');
  public function leftJoin($targetTable, $joinSql);
  public function rightJoin($targetTable, $joinSql);
  public function orderBy($columnName, $sort = 'ASC');
  public function groupBy($columnName);
  public function limit($start, $limit);
  public function sql($sqlq);
  public function all();
  public function first();
  public function rowCount();
  public function fetchCol();
  public function generateQuery();
  public function insert($tableName);
  public function set($data, $value = null);
  public function lastId();
  public function lastId2($data);
  public function lastId3();
  public function update($tableName);
  public function delete($tableName);
  public function done();
  public function total();
  public function pagination($totalRecord, $paginationLimit, $pageParamName);
  public function showPagination($url, $class = 'active');
  public function nextPage();
  public function prevPage();
  public function getSqlString();
  public function between($column, $values = []);
  public function notBetween($column, $values = []);
  public function findInSet($column, $value);
  public function findInSetReverse($column, $value);
  public function in($column, $value);
  public function notIn($column, $value);
  public function like($column, $value, $inner='all');
  public function notLike($column, $value, $inner='all');
  public function soundex($column, $value);
  public function truncate(string $tableName);
  public function truncateAll(array $dbs=[]);
  public function setAutoIncrement(string $tableName, int $ai=1);
}