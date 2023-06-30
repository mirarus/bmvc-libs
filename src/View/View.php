<?php

/**
 * View
 *
 * Mirarus BMVC
 * @package BMVC\Libs\View
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-core
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.5
 */

namespace BMVC\Libs\View;

use Exception;
use Closure;
use BMVC\Libs\FS;
use Jenssegers\Blade\Blade;

class View
{

  /**
   * @var array
   */
  private static $config = [];

  /**
   * @var null
   */
  private static $path = null;

  /**
   * @var false
   */
  private static $cache = false;

  /**
   * @var string
   */
  private static $cachePath = 'Cache';

  /**
   * @var string
   */
  private static $theme = 'default';

  /**
   * @var array
   */
  private static $themes = [];

  /**
   * @var string
   */
  private static $engine = 'php';

  /**
   * @var string[]
   */
  private static $engines = ['php', 'blade'];

  /**
   * @var string
   */
  private static $extension = 'php';

  /**
   * @var array
   */
  private static $data = [];

  /**
   * @var
   */
  private static $content;

  /**
   * @var
   */
  private static $layoutContent;

  /**
   * @param array $arr
   * @return void
   */
  public static function config(array $arr): void
  {
    self::$config = $arr;
    self::$path = array_key_exists('path', $arr) ? $arr['path'] : self::$path;
    self::$cache = array_key_exists('cache', $arr) ? $arr['cache'] : self::$cache;
    self::$theme = array_key_exists('theme', $arr) ? $arr['theme'] : self::$theme;
    self::$themes = array_key_exists('themes', $arr) ? $arr['themes'] : self::themes;
  }

  /**
   * @param $index
   * @return array|mixed
   */
  public static function getData($index = null)
  {
    return $index ? self::$data[$index] : self::$data;
  }

  /**
   * @param array $data
   * @return void
   */
  public static function setData(array $data): void
  {
    self::$data = $data;
  }

  /**
   * @return mixed
   */
  public static function getContent()
  {
    return self::$content;
  }

  /**
   * @return mixed
   */
  public function __toString()
  {
    return self::getContent();
  }

  /**
   * @param Closure $callback
   * @param $data
   * @return void
   * @throws Exception
   */
  public static function layout(Closure $callback, $data = null)
  {
    self::_data($data);

    list($_theme, $_themePath, $_themeLayout, $_themeLayoutFile) = self::_themeSelector();

    ob_start();
    call_user_func($callback);
    self::$content = $content = ob_get_contents();
    ob_end_clean();

    self::_ob($_themeLayoutFile, $_themeLayout, $data);
  }

  /**
   * @param $view
   * @param $data
   * @param bool $layout
   * @return void
   * @throws Exception
   */
  public static function load($view, $data = null, bool $layout = false)
  {
    self::_data($data);

    list($_theme, $_themePath, $_themeLayout, $_themeLayoutFile) = self::_themeSelector();
		self::setData(['theme' => $_theme]);
    ob_start();
    self::_import($_themePath, $view, $data);
    self::$content = $content = ob_get_contents();
    ob_end_clean();

    if ($layout) {
      self::_ob($_themeLayoutFile, $_themeLayout, $data);
    } else {
      echo $content;
    }
  }

  /**
   * @throws Exception
   */
  private static function _themeSelector(): array
  {
    $_theme = array_key_exists('theme', self::$data) ? self::$data['theme'] : self::$theme;
    $_theme = array_key_exists($_theme, self::$themes) ? $_theme : self::$theme;
    $_themePath = FS::trim(FS::implode([self::$path, self::$themes[$_theme]['path']]));
    $_themeLayout = FS::trim(FS::implode([$_themePath, self::$themes[$_theme]['layout']]));
    $_themeLayoutFile = FS::app($_themeLayout);

    if (!self::$config) throw new Exception('Viewer Config Not Found');
    if (!self::$themes[$_theme]) throw new Exception('Viewer Theme Not Found');
    if (!FS::is_file($_themeLayoutFile)) throw new Exception('Viewer Layout Not Found');

    return [$_theme, $_themePath, $_themeLayout, $_themeLayoutFile];
  }

  /**
   * @param $path
   * @param $view
   * @param $data
   * @param $return
   * @return void
   * @throws Exception
   */
  private static function _import($path, $view, $data = null, &$return = null): void
  {
    self::_data($data);

    if (self::$engine == 'php') {
      $return = self::_enginePHP($path, $view, $data);
    } elseif (self::$engine == 'blade') {
      $return = self::_engineBLADE($path, $view, $data);
    }
  }

  /**
   * @param string|null $path
   * @param string|null $view
   * @param $data
   * @return void
   * @throws Exception
   */
  private static function _enginePHP(string $path = null, string $view = null, $data = null)
  {
    $_file = FS::trim(FS::implode([$path, $view]));
    $_file = ($_file . '.' . self::$extension);
    $_vf = FS::app($_file);

    if (self::$cache) $_vf = self::_cache($view, $_vf, self::_cd($path));

    self::_ob($_vf, $_file, $data);
  }

  /**
   * @param string|null $path
   * @param string|null $view
   * @param $data
   * @return string
   */
  private static function _engineBLADE(string $path = null, string $view = null, $data = null): string
  {
    return (new Blade(FS::app($path), self::_cd($path)))->make($view, (array)$data)->render();
  }

  /**
   * @param string|null $path
   * @return string
   */
  private static function _cd(string $path = null): string
  {
    $path = FS::implode([($path ? $path : self::$path), self::$cachePath]);
    $path = FS::app($path);
    FS::mk_dir($path);
    return $path;
  }

  /**
   * @param $view
   * @param string $file
   * @param string $cachePath
   * @return string
   */
  private static function _cache($view, string $file, string $cachePath): string
  {
    if (FS::is_file($file)) {

      $_file = FS::implode([$cachePath, (md5($view) . '.' . self::$extension)]);
      $expiry = 120;

      if (!FS::is_file($_file) || (filemtime($_file) < (time() - $expiry))) {

        $signature = "<?php\n/**\n * @file " . $file . "\n * @date " . date(DATE_RFC822) . "\n * @expire " . date(DATE_RFC822, time() + $expiry) . "\n */\n?>\n";
        $content = $signature . file_get_contents($file);
        file_put_contents($_file, $content, LOCK_EX);
      }
      return $_file;
    }
    return $file;
  }

  /**
   * @param string $file
   * @param string $name
   * @param $data
   * @return void
   * @throws Exception
   */
  private static function _ob(string $file, string $name, $data = null): void
  {
    self::_data($data);

    if (FS::is_file($file)) {

      ob_start();
      require_once $file;
      $ob_content = ob_get_contents();
      ob_end_clean();

      if (isset($data['page_title'])) {
        $ob_content = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . (empty($data['page_title']) ? '$2' : $data['page_title'] . ' | $2') . '$3', $ob_content);
      }

      echo self::$layoutContent = $ob_content;
    } else {
      throw new Exception('View [' . $name . '] Not Found');
    }
  }

  /**
   * @param array $data
   * @return void
   */
  private static function _data(array &$data)
  {
    self::$data = $data = (array)array_merge($data, self::$data);
    //@extract($data);
    @$GLOBALS['view'] = $data;
    @$_REQUEST['view'] = $data;
  }

	/**
	 * @param string $theme
	 */
	public static function setTheme(string $theme)
	{
		if (self::getThemes($theme)) {
			self::$theme = $theme;
		}
	}

	/**
	 * @return string
	 */
	public static function getTheme(): string
	{
		return self::$theme;
	}

	/**
	 * @param mixed|null $index
	 * @return array
	 */
	public static function getThemes($index = null): array
	{
		return $index ? self::$themes[$index] : self::$themes;
	}

	/**
	 * @param string $file
	 * @param string|null $theme
	 * @return string
	 */
	public static function asset(string $file, string $theme = null): string
	{
		$_theme = $theme ?: self::$theme;
		$_theme = array_key_exists($_theme, self::$themes) ? $_theme : self::$theme;
		$_themePath = FS::trim(self::$themes[$_theme]['path']);
		return url("assets/" . $_themePath . '/' . $file);
	}
}