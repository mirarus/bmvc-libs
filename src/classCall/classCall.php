<?php

/**
 * classCall
 *
 * Mirarus BMVC
 * @package BMVC\Libs\classCall
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.7
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
        if ($new) {
            return new self;
        }
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
            return $return = call_user_func_array($action, $params ?? []);
        } else {

            $action = CL::replace($action);
            if (!$action) {
                return;
            }
            if (is_string($action) && self::$separators != null) {
                foreach (self::$separators as $separator) {
                    if (is_string($action) && strstr($action, $separator)) {
                        $action = explode($separator, $action);
                    }
                }
            }

            if (@is_array($action)) {
                if (count($action) > 1) {
                    $method = @array_pop($action);
                }
                $class = @array_pop($action);
            } else if (@is_string($action)) {
                $class = $action;
            }

            $namespace = CL::replace((is_array($action) ? CL::implode($action) : null));
            $class = CL::replace(($namespace ? CL::implode([$namespace, $class]) : $class));

            $_class = CL::replace((self::$called_class ? self::$called_class : get_called_class()));
            $_class = (new $_class)->import($class);

            if (is_string($action) || !isset($method)) {
                return $return = $_class;
            } elseif (is_object($_class) && class_exists(get_class($_class), false)) {
                return $return = method_exists($_class, $method) ? call_user_func_array([$_class, $method], $params ?? []) : $_class->{$method}();
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
    public static function get(string $action, object &$return = null)
    {
        $action = CL::explode(CL::replace($action));
        $class = ucfirst(array_pop($action));

        $_ns = self::$called_class ?: get_called_class();
        $_ns = CL::trim(CL::replace(self::$namespace[$_ns]));
        $ns = is_array($action) ? ucfirst(CL::implode($action)) : null;

        $class = $ns ? CL::implode([$ns, $class]) : $class;
        $class = $_ns ? CL::implode([$_ns, $class]) : $class;
        $class = CL::replace($class);
        $class = CL::implode([$_ns, CL::trim(str_replace($_ns, '', $class))]); // PHP 7.4 >

        $cls = new $class(is_array(self::$params) && !empty(self::$params));
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