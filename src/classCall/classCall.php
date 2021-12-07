<?php

/**
 * classCall
 *
 * Mirarus BMVC
 * @package BMVC\Libs\classCall
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
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
	 * @var array
	 */
	private static $separators = ['@', '/', '.', '::', ':'];
	private static $called_class;

	/**
	 * @param string $called_class
	 */
	public static function init(string $called_class): self
	{
		self::$called_class = $called_class;
		return new self;
	}

	/**
	 * @param string|null  $namespace
	 * @param bool|boolean $new
	 */
	public static function namespace(string $namespace=null, bool $new=false)
	{
		$_class = (self::$called_class ? self::$called_class : @get_called_class());
		self::$namespace[$_class] = CL::trim($namespace) . '\\';
		if ($new == true) return new self;
	}

	/**
	 * @param array $params
	 */
	public static function params(array $params=[])
	{
		self::$params = $params;
		return new self;
	}

	/**
	 * @param mixed       $action
	 * @param array|null  $params
	 * @param object|null &$return
	 */
	public static function call($action, array $params=null, object &$return=null)
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

			if ($action == null) return;

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
			} elseif (@is_string($action)) {
				$class = $action;
			}
			#
			$namespace = (($action != null && @is_array($action)) ? @CL::implode($action) : null);
			$namespace = CL::replace($namespace);
			$class		 = ($namespace != null) ? @CL::implode([$namespace, $class]) : $class;
			$class		 = CL::replace($class);
			#
			$_class = (self::$called_class ? self::$called_class : @get_called_class());
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
	 * @param  string      $type
	 * @param  string      $action
	 * @param  object|null &$return
	 * @return array
	 */
	public static function get(string $type, string $action, object &$return=null)
	{
		$action = CL::explode(CL::replace($action));
		$class  = @array_pop($action);
		
		$_namespace = (self::$called_class ? self::$called_class : @get_called_class());
		$_namespace = CL::trim(CL::replace(self::$namespace[$_namespace]));
		$namespace  = (($action != null && @is_array($action)) ? CL::implode($action) : null);
		$namespace  = CL::replace($namespace);
		$namespace  = @ucfirst($namespace);
		
		$class = @ucfirst($class);
		$class = ($namespace != null) ? CL::implode([$namespace, $class]) : $class;
		$class = ($_namespace != null) ? CL::implode([$_namespace, $class]) : $class;
		$class = CL::replace($class);

		$cls = (new $class((is_array(self::$params) && !empty(self::$params))));
	
		return $return = [
			'class' => $class,
			'cls' => $cls
		];
	}
}