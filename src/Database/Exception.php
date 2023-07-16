<?php

/**
 * Exception
 *
 * Mirarus Database Libs
 * @package Mirarus\DB
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/db
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Database;

class Exception extends \Exception
{

	/**
	 * @var string
	 */
	protected $class;

	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @param string $class
	 * @param string $message
	 * @param int|integer $code
	 */
	public function __construct(string $class, string $message, int $code = 0)
	{
		$message = "[" . $class . "] | " . $message;
		parent::__construct($message, $code);
		return;
	}
}