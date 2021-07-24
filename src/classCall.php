<?php

/**
 * classCall
 *
 * Mirarus BMVC
 * @package BMVC\Libs
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 1.8
 */

namespace BMVC\Libs;

class classCall
{

	/**
	 * @var array
	 */
	private static $namespace = [];

	/**
	 * @var array
	 */
	protected static $params = [];

	/**
	 * @var array
	 */
	private static $separators = ['@', '/', '.', '::', ':'];

	/**
	 * @param string|null $namespace
	 */
	public static function namespace(string $namespace=null, bool $new=false)
	{
		self::$namespace[@get_called_class()] = CL::trim($namespace) . '\\';
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
			$_class = get_called_class();
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
	protected static function get(string $type, string $action, object &$return=null)
	{
		$action = CL::replace($action);
		$action = CL::explode($action);
		$class  = @array_pop($action);
		#
		$_namespace = CL::trim(CL::replace(self::$namespace[@get_called_class()]));
		$namespace  = (($action != null && @is_array($action)) ? CL::implode($action) : null);
		$namespace  = CL::replace($namespace);
		$namespace  = @ucfirst($namespace);
		#
		$_type_  = '_' . strtolower($type) . '_';
		$_class_ = ($namespace != null) ? CL::implode([$namespace, $_type_]) : $_type_;
		$_class_ = CL::implode([$_namespace, $_class_]);
		$_class_ = CL::replace($_class_);
		if (@class_exists($_class_)) new $_class_;
		#
		$_class = @ucfirst($class);
		$_class = ($namespace != null) ? CL::implode([$namespace, $_class]) : $_class;
		$_class = CL::implode([$_namespace, $_class]);
		$_class = CL::replace($_class);
		#
		if (is_array(self::$params) && !empty(self::$params)) {
			$_cl = (new $_class(self::$params));
		} else {
			$_cl = (new $_class);
		}
		#
		return $return = [
			'_class_' => $_class_,
			'_class' => $_class,
			'_cl' => $_cl,
		];
	}
}