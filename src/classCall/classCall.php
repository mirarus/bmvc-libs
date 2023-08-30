<?php

/**
 * classCall
 *
 * Mirarus BMVC
 * @package BMVC\Libs\classCall
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.6
 */

namespace BMVC\Libs\classCall;

use BMVC\Libs\CL;

trait classCall
{

	/**
	 * @var array
	 */
	private static $namespace = [];

	/**
	 * @var array
	 */
	private static $params = [];


	/**
	 * @var string[]
	 */
	private static $separators = ['@', '/', '.', '::', ':'];

	/**
	 * @var
	 */
	private static $called_class;

	/**
	 * @param string $called_class
	 *
	 * @return static
	 */
	public static function init(string $called_class): self
	{
		self::$called_class = $called_class;
		return new self;
	}

	/**
	 * @param string|null $namespace
	 * @param bool $new
	 *
	 * @return void|static
	 */
	public static function namespace(string $namespace = null, bool $new = false)
	{
		$_class = (self::$called_class ? self::$called_class : @get_called_class());
		self::$namespace[$_class] = CL::trim($namespace) . '\\';
		if ($new)
			return new self;
	}

	/**
	 * @param array $params
	 *
	 * @return static
	 */
	public static function params(array $params): self
	{
		self::$params = $params;
		return new self;
	}

	/**
	 * @param $action
	 * @param array|null $params
	 * @param object|null $return
	 *
	 * @return mixed|void
	 */
	public static function call($action, array $params = null, object &$return = null)
	{
		if (is_callable($action)) {

			if ($params == null) {
				return $return = call_user_func($action);
			} else {
				return $return = call_user_func_array($action, array_values($params));
			}
		} else {

			$method = null;
			$class = null;
			$action = CL::replace($action);

			if ($action == null) {
				return;
			}
			if (@is_string($action)) {
				if (self::$separators != null) {
					foreach (self::$separators as $separator) {
						if (@is_string($action)) {
							if (@strstr($action, $separator)) {
								$action = @explode($separator, $action);
							}
						}
					}
				}
			}

			#
			if (@is_array($action)) {
				if (count($action) > 1) {
					$method = @array_pop($action);
				}
				$class = @array_pop($action);
			} else if (@is_string($action)) {
				$class = $action;
			}
			#
			$namespace = (($action != null && @is_array($action)) ? @CL::implode($action) : null);
			$namespace = CL::replace($namespace);
			$class = ($namespace != null) ? @CL::implode([$namespace, $class]) : $class;
			$class = CL::replace($class);
			#
			$_class = (self::$called_class ? self::$called_class : @get_called_class());
			$_class = CL::replace($_class);
			$_class = (new $_class);
			$_class = $_class->import($class);

			if (@is_string($action) || @!isset($method)) {
				return $return = $_class;
			} else {

				if (is_object($_class)) {
					if (class_exists(get_class($_class), false)) {
						if (method_exists($_class, $method)) {
							if ($params == null) {
								return $return = call_user_func([$_class, $method]);
							} else {
								return $return = call_user_func_array([$_class, $method], array_values($params));
							}
						} else {
							return $return = $_class->{$method}();
						}
					}
				}
			}
		}
	}

	/**
	 * @param string $type
	 * @param string $action
	 * @param object|null $return
	 *
	 * @return array
	 */
	public static function get(string $action, object &$return = null): array
	{
		$action = CL::explode(CL::replace($action));
		$class = @ucfirst(@array_pop($action));

		$_ns = (self::$called_class ? self::$called_class : @get_called_class());
		$_ns = CL::trim(CL::replace(self::$namespace[$_ns]));
		$ns = ((($action != null) && (gettype($action) == 'array')) ? CL::implode($action) : null);
		$ns = @ucfirst(CL::replace($ns));

		$class = ($ns != null) ? CL::implode([$ns, $class]) : $class;
		$class = ($_ns != null) ? CL::implode([$_ns, $class]) : $class;
		$class = CL::replace($class);
		$cls = (new $class((is_array(self::$params) && !empty(self::$params))));
		@header("Last-Modified: " . date("D, d M Y H:i:s") . " GMT");

		return $return = [
			'class' => $class,
			'cls' => $cls
		];
	}

	/**
	 * @param string $class
	 * @param object|null $return
	 *
	 * @return mixed
	 */
	public static function import(string $class, object &$return = null)
	{
		self::get($class, $get);

		return $return = @$get['cls'];
	}
}