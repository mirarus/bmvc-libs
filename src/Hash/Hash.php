<?php

/**
 * Hash
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Hash
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Hash;

use Exception;

class Hash
{

	/**
	 * @var integer
	 */
	private static $cost = 12;

	/**
	 * @param string     $value
	 * @param array|null $options
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function make(string $value, array $options = null)
	{
		if (!array_key_exists('cost', $options)) { // @phpstan-ignore-line
			$options['cost'] = self::$cost;
		}
		$hash = password_hash($value, PASSWORD_DEFAULT, $options);
		if ($hash === false) {
			throw new Exception('Hash Error! | Bcrypt hash is not supported.');
		}
		return $hash;
	}

	/**
	 * @param  string $value
	 * @param  string $hashedValue
	 * @return boolean
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function check(string $value, string $hashedValue): bool
	{
		return password_verify($value, $hashedValue);
	}

	/**
	 * @param string     $hashedValue
	 * @param array|null $options
	 *
	 * @phpstan-ignore-next-line
	 */
	public static function rehash(string $hashedValue, array $options = null)
	{
		if (!array_key_exists('cost', $options)) { // @phpstan-ignore-line
			$options['cost'] = self::$cost;
		}
		return password_needs_rehash($hashedValue, PASSWORD_DEFAULT, $options);
	}
}