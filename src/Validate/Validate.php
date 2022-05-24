<?php

/**
 * Validate
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Validate
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace BMVC\Libs\Validate;

class Validate
{

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function is_nem($arg): bool
  {
    return (isset($arg) && !empty($arg));
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function nis_em($arg): bool
  {
    return (!isset($arg) && empty($arg));
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function is($arg): bool
  {
    return isset($arg);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function nis($arg): bool
  {
    return !isset($arg);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function nem($arg): bool
  {
    return !empty($arg);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function em($arg): bool
  {
    return empty($arg);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function array($arg): bool
  {
    return is_array($arg);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function integer($arg): bool
  {
    return filter_var($arg, FILTER_VALIDATE_INT);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function float($arg): bool
  {
    return filter_var($arg, FILTER_VALIDATE_FLOAT);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function numeric($arg): bool
  {
    return is_numeric($arg);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function url($arg): bool
  {
    return filter_var($arg, FILTER_VALIDATE_URL);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function ip_adress($arg): bool
  {
    return filter_var($arg, FILTER_VALIDATE_IP);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function ipv4($arg): bool
  {
    return filter_var($arg, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function ipv6($arg): bool
  {
    return filter_var($arg, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function email($arg): bool
  {
    return filter_var($arg, FILTER_VALIDATE_EMAIL);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function phone($arg): bool
  {
    return filter_var($arg, FILTER_SANITIZE_NUMBER_INT);
  }

  /**
   * @param string|array $arg
   * @return mixed
   */
  public static function sanitize($arg) // @phpstan-ignore-line
  {
    if (!is_array($arg)) {
      return filter_var(trim($arg), FILTER_SANITIZE_STRING);
    }
    foreach ($arg as $key => $val) {
      $arg[$key] = filter_var($val, FILTER_SANITIZE_STRING);
    }
    return $arg;
  }

  /**
   * @param string|array $arg
   * @return boolean
   */
  public static function nullable($arg): bool // @phpstan-ignore-line
  {
    return (is_array($arg) ? (empty($arg) === true) : (trim($arg) === ''));
  }

  /**
   * @param string|array $arg
   * @return boolean
   */
  public static function required($arg): bool // @phpstan-ignore-line
  {
    return (is_array($arg) ? (empty($arg) === false) : (trim($arg) !== ''));
  }

  /**
   * @param string $arg
   * @param int $length
   * @return boolean
   */
  public static function min_len(string $arg, int $length): bool
  {
    return ((strlen(trim($arg)) < $length) === false);
  }

  /**
   * @param string $arg
   * @param int $length
   * @return boolean
   */
  public static function max_len(string $arg, int $length): bool
  {
    return (strlen(trim($arg)) > $length) === false;
  }

  /**
   * @param string $arg
   * @param int $length
   * @return boolean
   */
  public static function exact_len(string $arg, int $length): bool
  {
    return (strlen(trim($arg)) == $length) !== false;
  }

  /**
   * @param mixed $arg
   * @return bool
   */
  public static function alpha($arg): bool
  {
    if (!is_string($arg))
      return false;
    return ctype_alpha($arg);
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function alpha_num($arg): bool
  {
    return ctype_alnum($arg);
  }

  /**
   * @param string $arg
   * @return boolean
   */
  public static function alpha_dash(string $arg): bool
  {
    return (!preg_match("/^([-a-z0-9_-])+$/i", $arg)) ? false : true;
  }

  /**
   * @param string $arg
   * @return boolean
   */
  public static function alpha_space(string $arg): bool
  {
    return (!preg_match("/^([A-Za-z0-9- ])+$/i", $arg)) ? false : true;
  }

  /**
   * @param mixed $arg
   * @return boolean
   */
  public static function boolean($arg): bool
  {
    $acceptable = [true, false, 0, 1, '0', '1'];
    return in_array($arg, $acceptable, true);
  }

  /**
   * @param mixed $arg
   * @param mixed $min
   * @return boolean
   */
  public static function min_numeric($arg, $min): bool
  {
    return (is_numeric($arg) && is_numeric($min) && $arg >= $min) !== false;
  }

  /**
   * @param mixed $arg
   * @param mixed $max
   * @return boolean
   */
  public static function max_numeric($arg, $max): bool
  {
    return (is_numeric($arg) && is_numeric($max) && $arg <= $max) !== false;
  }

  /**
   * @param string $arg
   * @param int|string $part
   * @return boolean
   */
  public static function contains(string $arg, $part): bool
  {
    return strpos($arg, $part) !== false;
  }

  /**
   * @param mixed $arg
   * @param mixed $field
   * @return boolean
   */
  public static function matches($arg, $field): bool
  {
    return ($arg == $field) !== false;
  }

  /**
   * @param string $str
   * @return mixed
   */
  public static function initials(string $str)
  {
    $ret = '';
    foreach (explode(' ', $str) as $Word) {
      $ret .= strtoupper($Word[0]);
    }
    return $ret;
  }

  /**
   * @param mixed $var
   * @return boolean
   */
  public static function check($var): bool
  {
    $var = str_replace("\n", " ", $var); // @phpstan-ignore-line
    $var = str_replace(" ", "", $var);
    return (isset($var) && !empty($var) && $var != ''); // @phpstan-ignore-line
  }

  /**
   * @param string $arg
   * @return boolean
   */
  public static function cc(string $arg): bool
  {
    $number = (string)preg_replace('/\D/', '', $arg);
    if (function_exists('mb_strlen')) {
      $length = mb_strlen($number);
    } else {
      $length = strlen($number);
    }
    $parity = $length % 2;
    $total = 0;
    for ($i = 0; $i < $length; $i++) {
      $digit = $number[$i];
      if ($i % 2 == $parity) {
        $digit *= 2; // @phpstan-ignore-line
        if ($digit > 9) {
          $digit -= 9;
        }
      }
      $total += $digit;
    }
    return (bool)$total % 10 == 0;
  }
}