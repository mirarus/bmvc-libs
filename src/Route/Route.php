<?php

/**
 * Route
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Route
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-core
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.6
 */

namespace BMVC\Libs\Route;

use Closure;
use BMVC\Libs\Util;
use BMVC\Libs\Request;
use BMVC\Libs\Response;
use BMVC\Libs\MError;

class Route implements IRoute, IMethod
{
  use Method;

  /**
   * @var
   */
  public static $error_page;

  /**
   * @var
   */
  public static $error_server;

  /**
   * @var array
   */
  private static $routes = [];

  /**
   * @var array
   */
  private static $groups = [];

  /**
   * @var array
   */
  private static $middlewares = [];

  /**
   * @var string
   */
  private static $prefix = '/';

  /**
   * @var string
   */
  private static $ip;

  /**
   * @var string
   */
  private static $return;

  /**
   * @var array
   */
  private static $namespaces = [];

  /**
   * @var integer
   */
  private static $groupped = 0;

  /**
   * @var string
   */
  private static $mainRoute = '/';

  /**
   * @var array
   *
   * @phpstan-ignore-next-line
   */
  private static $patterns = [
    ':all' => '(.*)',
    ':num' => '([0-9]+)',
    ':id' => '([0-9]+)',
    ':alpha' => '([a-zA-Z]+)',
    ':alpnum' => '([a-zA-Z0-9_-]+)',
    ':lowercase' => '([a-z]+)',
    ':uppercase' => '([A-Z]+)',

    '{all}' => '(.*)',
    '{num}' => '([0-9]+)',
    '{id}' => '([0-9]+)',
    '{alpha}' => '([a-zA-Z]+)',
    '{alpnum}' => '([a-zA-Z0-9_-]+)',
    '{lowercase}' => '([a-z]+)',
    '{uppercase}' => '([A-Z]+)',
  ];

  /**
   * @param array|null $return
   *
   * @return array|null (mixed|null|string|string[])[]|null
   *
   * @phpstan-ignore-next-line
   *
   */
  public static function Run(array &$return = null): ?array
  {
    $routes = (array)self::$routes;
    $match = false;

    if ($routes && $routes != null) {

      foreach ($routes as $route) {

        $method = $route['method'];
        $action = $route['callback'];
        $url = $route['pattern'];
        $ip = ($route['ip'] ?? null);
        $_return = ($route['return'] ?? null);
        $namespaces = ($route['namespaces'] ?? null);
        $middlewares = ($route['middlewares'] ?? null);

        if (preg_match("#^{$url}$#", ('/' . Util::get_url()), $params)) {

          if ($method === @Request::getRequestMethod() && @Request::checkIp($ip)) {

            $match = true;
            array_shift($params);

            return $return = [
              'route' => $route,
              'method' => $method,
              'action' => $action,
              'params' => $params,
              'namespaces' => $namespaces,
              'middlewares' => $middlewares,
              'url' => $url,
              '_url' => Util::get_url(),
              '_return' => $_return,
            ];
          }
        }
      }
    }
    // @phpstan-ignore-next-line
    if (!$match) self::get_404();
  }

  /**
   * @param string $method
   * @param string|null $pattern
   * @param $callback
   * @return void
   */
  private static function set(string $method, string $pattern = null, $callback): void
  {
    $closure = null;
    if ($pattern == '/') {
      $pattern = self::$prefix . trim((string)$pattern, '/');
    } else {
      if (self::$prefix == '/') {
        $pattern = self::$prefix . trim((string)$pattern, '/');
      } else {
        $pattern = self::$prefix . $pattern;
      }
    }

    foreach (self::$patterns as $key => $value) {
      $pattern = @strtr($pattern, [$key => $value]);
    }
    if (is_callable($callback)) {
      $closure = $callback;
    } elseif (is_string($callback)) {
      if (stripos($callback, '@') !== false) {
        $closure = $callback;
      } elseif (stripos($callback, '/') !== false) {
        $closure = $callback;
      } elseif (stripos($callback, '.') !== false) {
        $closure = $callback;
      } elseif (stripos($callback, '::') !== false) {
        $closure = $callback;
      } elseif (stripos($callback, ':') !== false) {
        $closure = $callback;
      }
    } elseif (is_array($callback)) {
      $closure = $callback[0] . ':' . $callback[1];
    }

    if ($closure) {
      $route_ = [
        'method' => $method,
        'pattern' => $pattern,
        'callback' => @$closure
      ];

      if (self::$ip) $route_['ip'] = self::$ip;
      if (self::$middlewares) $route_['middlewares'] = self::$middlewares;
      if (self::$return) $route_['return'] = self::$return;
      if (self::$namespaces) $route_['namespaces'] = self::$namespaces;

      self::$routes[] = $route_;
    }
  }

  /**
   * @param Closure $callback
   * @return void
   */
  public static function group(Closure $callback): void
  {
    self::$groupped++;
    self::$groups[] = [
      'baseRoute' => self::$prefix,
      'middlewares' => self::$middlewares,
      'ip' => self::$ip,
      'return' => self::$return,
      'namespaces' => self::$namespaces
    ];
    call_user_func($callback);
    if (self::$groupped > 0) {
      self::$prefix = self::$groups[self::$groupped - 1]['baseRoute'];
      self::$middlewares = self::$groups[self::$groupped - 1]['middlewares'];
      self::$ip = self::$groups[self::$groupped - 1]['ip'];
      self::$return = self::$groups[self::$groupped - 1]['return'];
      //self::$namespaces		= self::$groups[self::$groupped-1]['namespaces'];
    }
    self::$groupped--;
    if (self::$groupped <= 0) {
      self::$prefix = '/';
      self::$middlewares = [];
      self::$ip = '';
      self::$return = '';
      self::$namespaces = [];
    }
    self::$prefix = @self::$groups[self::$groupped - 1]['baseRoute'];
  }

  /**
   * @param array $expressions
   * @return static
   */
  public static function where(array $expressions): self
  {
    $routeKey = array_search(end(self::$routes), self::$routes);
    $pattern = Util::parse_uri(self::$routes[$routeKey]['pattern'], $expressions);
    $pattern = '/' . implode('/', $pattern);
    $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
    self::$routes[$routeKey]['pattern'] = $pattern;
    return new self;
  }


  /**
   * @param string $name
   * @param array|null $params
   * @return static
   */
  public static function name(string $name, array $params = null): self
  {
    $routeKey = array_search(end(self::$routes), self::$routes);
    self::$routes[$routeKey]['name'] = $name;
    return new self;
  }

  /**
   * @param string $name
   * @param array|null $params
   * @return string
   */
  public static function url(string $name, array $params = null): string
  {
    $pattern = "";
    foreach (self::$routes as $route) {
      if (array_key_exists('name', $route) && $route['name'] == $name) {
        $pattern = $route['pattern'];
        $pattern = Util::parse_uri($pattern, $params);
        $pattern = implode('/', $pattern);
        break;
      }
    }
    return $pattern;
  }

  /**
   * @return array
   */
  public static function routes(): array
  {
    return self::$routes;
  }

  /**
   * @param $callback
   * @return static
   */
  public static function set_404($callback): self
  {
    self::$error_page = $callback;
    return new self;
  }

  /**
   * @return void
   */
  public static function get_404()
  {
    if (@self::$error_page) {
      return self::$error_page;
    } else {
      Response::setStatusCode(404);
      $res_txt = (Response::getStatusCode() . ' ' . Response::getStatusMessage());
      if (Request::isGet()) {
        MError::print($res_txt, (Util::get_url() ? 'Page: ' . Util::get_url() : null), true, Response::getStatusMessage(), null, true, 404);
      } else {
        echo Response::_json((Util::get_url() ? [
          'message' => $res_txt,
          'page' => Util::get_url()
        ] : [
          'message' => $res_txt
        ]), 404);
      }
    }
  }

  /**
   * @param $callback
   * @return static
   */
  public static function set_500($callback): self
  {
    self::$error_server = $callback;
    return new self;
  }

  /**
   * @return void
   */
  public static function get_500()
  {
    if (@self::$error_server) {
      return self::$error_server;
    } else {
      Response::setStatusCode(500);
      $res_txt = (Response::getStatusCode() . ' ' . Response::getStatusMessage());
      if (Request::isGet()) {
        MError::print($res_txt, null, true, Response::getStatusMessage(), null, true, 500);
      } else {
        echo Response::_json((Util::get_url() ? [
          'message' => $res_txt,
          'page' => Util::get_url()
        ] : [
          'message' => $res_txt
        ]), 500);
      }
    }
  }

  /**
   * @param string $type
   * @param int $code
   * @param $callback
   * @return Route|mixed|void
   */
  public static function error(string $type = 'get', int $code = 404, $callback = null)
  {
    if ($type == 'get') {
      if ($code == 404) {
        self::get_404();
      } elseif ($code == 500) {
        self::get_500();
      }
    } elseif ($type == 'set') {
      if ($code == 404) {
        self::set_404($callback);
      } elseif ($code == 500) {
        self::set_500($callback);
      }
    }
  }

  /**
   * @param array $urls
   * @param string $url
   * @param string $type
   * @return void
   */
  public static function url_check(array $urls, string $url, string $type = '404')
  {
    if (!in_array($url, $urls)) {
      if ($type == '404') {
        self::get_404();
      } elseif ($type == '500') {
        self::get_500();
      }
    }
  }
}