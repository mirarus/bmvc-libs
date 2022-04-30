<?php

/**
 * _MError
 *
 * Mirarus BMVC
 * @package BMVC\Libs\MError
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
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
    array_map(function ($key, $value) {
      if ($key == 'color') {
        return $this->setColor($value);
      }
      if ($key == 'html') {
        return $this->setHtml($value);
      }
      if ($key == 'title') {
        return $this->setTitle($value);
      }
      if ($key == 'stop') {
        return $this->setStop($value);
      }
    }, array_keys($array), array_values($array));

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
   * @param bool $bool
   * @return $this
   */
  public function setHtml(bool $bool = false): self
  {
    self::$html = $bool;
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
   * @param bool $stop
   * @return $this
   */
  public function setStop(bool $stop = true): self
  {
    self::$stop = $stop;
    return $this;
  }
}