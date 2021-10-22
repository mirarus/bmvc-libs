<?php

/**
 * _MError
 *
 * Mirarus BMVC
 * @package BMVC\Libs\MError
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\MError;

class _MError extends MError
{

	/**
	 * @param array $array
	 */
	public function setData(array $array): self
	{
		array_map(function ($key, $value) {
			if ($key == 'color') {
				return $this->setColor($value);
			} if ($key == 'html') {
				return $this->setHtml($value);
			} if ($key == 'title') {
				return $this->setTitle($value);
			} if ($key == 'stop') {
				return $this->setStop($value);
			}
		}, array_keys($array), array_values($array));

		return $this;
	}

	/**
	 * @param string $color
	 */
	public function setColor(string $color): self
	{
		self::$color = self::$colors[$color] ? self::$colors[$color] : self::$colors['info'];
		return $this;
	}

	/**
	 * @param bool|boolean $bool
	 */
	public function setHtml(bool $bool=false): self
	{
		self::$html = $bool;
		return $this;
	}

	/**
	 * @param string $title
	 */
	public function setTitle(string $title): self
	{
		self::$title = $title;
		return $this;
	}

	/**
	 * @param bool|boolean $stop
	 */
	public function setStop(bool $stop=true): self
	{
		self::$stop = $stop;
		return $this;
	}
}