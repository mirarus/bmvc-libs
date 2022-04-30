<?php

/**
 * Str
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Str
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Str;

class Str
{

  /**
   * @param string $string
   * @param int $start
   * @param int $length
   * @param string|null $encoding
   * @return string
   */
  public static function substr(string $string, int $start, int $length = 0, string $encoding = null): string
  {
    $encoding = $encoding == null ? "UTF-8" : null;

    if (function_exists('mb_substr')) {
      return mb_substr($string, $start, $length, (string)$encoding);
    } else {
      return substr($string, $start, $length);
    }
  }

  /**
   * @param string $str
   * @return string
   */
  public static function replace_tr(string $str): string
  {
    $str = trim($str);
    $search = ['Ç', 'ç', 'Ğ', 'ğ', 'ı', 'İ', 'Ö', 'ö', 'Ş', 'ş', 'Ü', 'ü'];
    $replace = ['C', 'c', 'G', 'g', 'i', 'I', 'O', 'o', 'S', 's', 'U', 'u'];
    return str_replace($search, $replace, $str);
  }

  /**
   * @param string $str
   * @param string|null $encoding
   * @return string
   */
  public static function mb_strtoupper(string $str, string $encoding = null): string
  {
    $encoding = $encoding == null ? "UTF-8" : null;

    $str = trim($str);
    $search = ['ı', 'i'];
    $replace = ['I', 'İ'];
    $str = str_replace($search, $replace, $str);

    if (function_exists('mb_strtoupper')) {
      return mb_strtoupper($str, (string)$encoding);
    } else {
      return $str;
    }
  }

  /**
   * @param int $var
   * @param string $pattern
   * @return string
   */
  public static function code_gen(int $var, string $pattern = 'alpnum'): string
  {
    $chars = [];
    if ($pattern == 'alpnum') {
      $chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
    } elseif ($pattern == 'alpha') {
      $chars = array_merge(range('a', 'z'), range('A', 'Z'));
    } elseif ($pattern == 'num') {
      $chars = array_merge(range(0, 9));
    } elseif ($pattern == 'lowercase') {
      $chars = array_merge(range('a', 'z'));
    } elseif ($pattern == 'uppercase') {
      $chars = array_merge(range('A', 'Z'));
    }
    srand((int)microtime() * 100000);
    shuffle($chars);
    $result = '';
    for ($i = 0; $i < $var; $i++) {
      $result .= $chars[$i];
    }
    unset($chars);
    return ($result);
  }

  /**
   * @param int $int
   * @return string
   */
  public static function unique_key(int $int = 10): string
  {
    return hash('sha512', session_id() . bin2hex((string)openssl_random_pseudo_bytes($int)));
  }

  /**
   * @param string $str
   * @param array|null $options
   * @param string|null $encoding
   * @return string
   */
  public static function slug(string $str, array $options = null, string $encoding = null): string
  {
    $encoding = $encoding == null ? "UTF-8" : null;

    $str = (string)$str;
    $encoding = (string)$encoding;

    if (function_exists('mb_convert_encoding')) {
      $str = mb_convert_encoding($str, $encoding, mb_list_encodings());
    }

    $defaults = array(
      'delimiter' => '-',
      'limit' => null,
      'lowercase' => true,
      'replacements' => array(),
      'transliterate' => true
    );
    $options = array_merge($defaults, $options);

    $char_map = array(
      'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
      'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
      'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
      'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
      'ß' => 'ss',
      'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
      'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
      'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
      'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
      'ÿ' => 'y',
      '©' => '(c)',
      'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
      'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
      'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
      'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
      'Ϋ' => 'Y',
      'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
      'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
      'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
      'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
      'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
      'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
      'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
      'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
      'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
      'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
      'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
      'Я' => 'Ya',
      'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
      'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
      'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
      'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
      'я' => 'ya',
      'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
      'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
      'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
      'Ž' => 'Z',
      'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
      'ž' => 'z',
      'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
      'Ż' => 'Z',
      'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
      'ż' => 'z',
      'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
      'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
      'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
      'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );
    $str = (string)preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    if ($options['transliterate']) {
      $str = (string)str_replace(array_keys($char_map), $char_map, $str);
    }
    $str = (string)preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    $str = (string)preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    if (function_exists('mb_substr')) {
      $str = mb_substr((string)$str, 0, ($options['limit'] ? $options['limit'] : mb_strlen((string)$str, (string)$encoding)), (string)$encoding);
    }
    $str = trim((string)$str, $options['delimiter']);
    if (function_exists('mb_strtolower')) {
      $str = $options['lowercase'] ? mb_strtolower($str, (string)$encoding) : $str;
    }
    return $str;
  }
}