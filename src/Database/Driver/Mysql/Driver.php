<?php

/**
 * Mysql
 *
 * Mirarus Database Libs
 * @package Mirarus\DB\Driver\Mysql
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.5
 */

namespace Mirarus\DB\Driver\Mysql;

use Mirarus\DB\DB;
use Mirarus\DB\Exception;
use Mirarus\DB\Interfaces\Driver\Mysql\Driver as IDriver;
use PDO;
use PDOException;

class Driver extends Connect implements IDriver
{

	private static
	$type, // @phpstan-ignore-line
	$sql, // @phpstan-ignore-line
	$unionSql, // @phpstan-ignore-line
	$tableName, // @phpstan-ignore-line
	$where, // @phpstan-ignore-line
	$having, // @phpstan-ignore-line
	$grouped, // @phpstan-ignore-line
	$group_id, // @phpstan-ignore-line
	$join, // @phpstan-ignore-line
	$orderBy, // @phpstan-ignore-line
	$groupBy, // @phpstan-ignore-line
	$limit, // @phpstan-ignore-line
	$sqlq, // @phpstan-ignore-line
	$page, // @phpstan-ignore-line
	$totalRecord, // @phpstan-ignore-line
	$paginationLimit, // @phpstan-ignore-line
	$html; // @phpstan-ignore-line
	public static $queries = []; // @phpstan-ignore-line
	public static $reference = ['NOW()']; // @phpstan-ignore-line

	/**
	 * @param string $tableName
	 */
	public function from(string $tableName) // @phpstan-ignore-line
	{
		$this->sql = 'SELECT * FROM ' . $tableName;
		$this->tableName = $tableName;
		DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
		return $this;
	}

	/**
	 * @param string $tableName
	 */
	public function countFrom(string $tableName) // @phpstan-ignore-line
	{
		$this->sql = 'SELECT COUNT(*) FROM ' . $tableName;
		$this->tableName = $tableName;
		DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
		return $this;
	}

	/**
	 * @param string $columns
	 */
	public function select(string $columns) // @phpstan-ignore-line
	{
		$this->sql = str_replace(' * ', ' ' . $columns . ' ', $this->sql);
		DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
		return $this;
	}

	/**
	 * @param string $column
	 * @param string $value
	 * @param string $mark
	 * @param string $logical
	 */
	public function where(string $column, string $value = '', string $mark = '=', string $logical = '&&') // @phpstan-ignore-line
	{
		$this->where[] = [
			'column' => $column,
			'value' => $value,
			'mark' => $mark,
			'logical' => $logical,
			'grouped' => $this->grouped,
			'group_id' => $this->group_id
		];
		DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
		return $this;
	}

	/**
	 * @param string $tableName
	 */
	public function insert(string $tableName) // @phpstan-ignore-line
	{
		$this->sql = 'INSERT INTO ' . $tableName;
		DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
		return $this;
	}

	/**
	 * @param string $tableName
	 */
	public function update(string $tableName) // @phpstan-ignore-line
	{
		$this->sql = 'UPDATE ' . $tableName;
		DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
		return $this;
	}

	/**
	 * @param string $tableName
	 */
	public function delete(string $tableName) // @phpstan-ignore-line
	{
		$this->sql = 'DELETE FROM ' . $tableName;
		DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
		return $this;
	}

	public function set($data, $value = null) // @phpstan-ignore-line
	{
		try {

			if ($value) {

				if (strstr($value, '+')) {
					$this->sql .= ' SET ' . $data . ' = ' . $data . ' ' . $value;
					$executeValue = null;
				} elseif (strstr($value, '-')) {
					$this->sql .= ' SET ' . $data . ' = ' . $data . ' ' . $value;
					$executeValue = null;
				} else {
					$this->sql .= ' SET ' . $data . ' = :' . $data . '';
					$executeValue = [
						$data => $value
					];
				}
			} else {

				$this->sql .= ' SET ' . implode(', ', array_map(function ($item) {
					return $item . ' = :' . $item;
				}, array_keys($data)));
				$executeValue = $data;
			}

			$result = $this->conn->prepare($this->sql)->execute($executeValue);

			DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);

			if (strstr($this->sql, 'INSERT INTO ')) {
				return $this->conn->lastInsertId();
			} else {
				return $result;
			}
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	public function done() // @phpstan-ignore-line
	{
		try {
			$this->getWhere('where');
			$this->getWhere('having');
			$result = $this->conn->exec($this->sql);
			DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
			return $result;
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	public function all() // @phpstan-ignore-line
	{
		try {
			$result = $this->genQuery()->fetchAll(PDO::FETCH_ASSOC);
			DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
			return $result;
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	public function first() // @phpstan-ignore-line
	{
		try {
			$result = $this->genQuery()->fetch(PDO::FETCH_ASSOC);
			DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
			return $result;
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	private function genQuery() // @phpstan-ignore-line
	{
		try {
			if ($this->join) {
				$this->sql .= implode(' ', $this->join);
				$this->join = null;
			}
			$this->getWhere('where');
			if ($this->groupBy) {
				$this->sql .= $this->groupBy;
				$this->groupBy = null;
			}
			$this->getWhere('having');
			if ($this->orderBy) {
				$this->sql .= $this->orderBy;
				$this->orderBy = null;
			}
			if ($this->limit) {
				$this->sql .= $this->limit;
				$this->limit = null;
			}
			if ($this->type == 'union') {
				$this->sql = $this->unionSql . ' UNION ALL ' . $this->sql;
			}
			$this->type = '';
			$query = $this->conn->query($this->sql);

			DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);

			return $query;
		} catch (PDOException $e) {
			$this->showError($e);
		}
	}

	/**
	 * @param string $conditionType
	 */
	private function getWhere(string $conditionType = 'where'): void
	{
		if ((is_array($this->{$conditionType}) && count($this->{$conditionType}) > 0)) {
			$whereClause = ' ' . ($conditionType == 'having' ? 'HAVING' : 'WHERE') . ' ';
			$arrs = $this->{$conditionType};
			if (is_array($arrs)) {
				foreach ($arrs as $key => $item) {
					if ($item['grouped'] === true && (((isset($arrs[$key - 1]) && $arrs[$key - 1]['grouped'] !== true) || (isset($arrs[$key - 1]) && $arrs[$key - 1]['group_id'] != $item['group_id'])) || ((isset($arrs[$key - 1]) && $arrs[$key - 1]['grouped'] !== true) || (!isset($arrs[$key - 1]))))) {
						$whereClause .= (isset($arrs[$key - 1]) && $arrs[$key - 1]['grouped'] == true ? ' ' . $item['logical'] : null) . ' (';
					}
					switch ($item['mark']) {
						case 'LIKE':
						$where = $item['column'] . ' LIKE "' . $item['value'] . '"';
						break;
						case 'NOT LIKE':
						$where = $item['column'] . ' NOT LIKE "' . $item['value'] . '"';
						break;
						case 'BETWEEN':
						$where = $item['column'] . ' BETWEEN "' . $item['value'][0] . '" AND "' . $item['value'][1] . '"';
						break;
						case 'NOT BETWEEN':
						$where = $item['column'] . ' NOT BETWEEN "' . $item['value'][0] . '" AND "' . $item['value'][1] . '"';
						break;
						case 'FIND_IN_SET':
						$where = 'FIND_IN_SET(' . $item['column'] . ', ' . $item['value'] . ')';
						break;
						case 'FIND_IN_SET_REVERSE':
						$where = 'FIND_IN_SET(' . $item['value'] . ', ' . $item['column'] . ')';
						break;
						case 'IN':
						$where = $item['column'] . ' IN("' . (is_array($item['value']) ? implode('", "', $item['value']) : $item['value']) . '")';
						break;
						case 'NOT IN':
						$where = $item['column'] . ' NOT IN(' . (is_array($item['value']) ? implode(', ', $item['value']) : $item['value']) . ')';
						break;
						case 'SOUNDEX':
						$where = 'SOUNDEX(' . $item['column'] . ') LIKE CONCAT(\'%\', TRIM(TRAILING \'0\' FROM SOUNDEX(\'' . $item['value'] . '\')), \'%\')';
						break;
						default:
						$where = $item['column'] . ' ' . $item['mark'] . ' ' . (@preg_grep('/' . trim($item['value']) . '/i', $this->reference) ? $item['value'] : '"' . $item['value'] . '"');
						break;
					}
					if ($key == 0) {
						if ($item['grouped'] == false && isset($arrs[$key + 1]['grouped']) == true) {
							$whereClause .= $where . ' ' . $item['logical'];
						} else {
							$whereClause .= $where;
						}
					} else {
						$whereClause .= ' ' . $item['logical'] . ' ' . $where;
					}
					if ($item['grouped'] === true && (((isset($arrs[$key + 1]) && $arrs[$key + 1]['grouped'] !== true) || ($item['grouped'] === true && !isset($arrs[$key + 1]))) || ((isset($arrs[$key + 1]) && $arrs[$key + 1]['group_id'] != $item['group_id']) || ($item['grouped'] === true && !isset($arrs[$key + 1]))))) {
						$whereClause .= ' )';
					}
				}
			}
			$whereClause = rtrim($whereClause, '||');
			$whereClause = rtrim($whereClause, '&&');
			$whereClause = preg_replace('/\(\s+(\|\||&&)/', '(', $whereClause);
			$whereClause = preg_replace('/(\|\||&&)\s+\)/', ')', (string) $whereClause);
			$this->sql .= $whereClause;
			$this->unionSql .= $whereClause;
			$this->{$conditionType} = null;
			DB::setTime(microtime(true), __METHOD__, __NAMESPACE__);
		}
	}

	/**
	 * @param  PDOException $error
	 * @return _Exception
	 */
	private function showError(PDOException $error): Exception
	{
		$this->error = $error->getMessage();
		throw new Exception(self::get('driver'), $error->getMessage());
	}
}