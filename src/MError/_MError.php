<?php

/**
 * _MError
 *
 * Mirarus BMVC
 * @package BMVC\Libs\MError
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.3
 */

namespace BMVC\Libs\MError;

class _MError extends MError
{

  /**
   * @param array $array
   * @return $this
   */
  public function setData(array $array): self
  {
    array_map(function ($key, $val) {
      if ($key == 'html') return $this->setHtml($val);
      if ($key == 'stop') return $this->setStop($val);
      if ($key == 'title') return $this->setTitle($val);
      if ($key == 'color') return $this->setColor($val);
      if ($key == 'code') return $this->setCode($val);
    }, array_keys($array), array_values($array));

    return $this;
  }

  /**
   * @param bool $html
   * @return $this
   */
  public function setHtml(bool $html = true): self
  {
    self::$html = $html;
    return $this;
  }

  /**
   * @param bool $stop
   * @return $this
   */
  public function setStop(bool $stop = true): self
  {
    self::$stop = $stop;
    return $this;
  }

  /**
   * @param string $title
   * @return $this
   */
  public function setTitle(string $title): self
  {
    self::$title = $title;
    return $this;
  }

  /**
   * @param string $color
   * @return $this
   */
  public function setColor(string $color): self
  {
    self::$color = self::$colors[$color] ? self::$colors[$color] : self::$colors['info'];
    return $this;
  }

  /**
   * @param int $code
   * @return $this
   */
  public function setCode(int $code = 200): self
  {
    self::$code = $code;
    return $this;
  }
}