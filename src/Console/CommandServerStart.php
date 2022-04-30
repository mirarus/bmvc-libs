<?php

/**
 * Command Server Start
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Console
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Console;

use BMVC\Libs\FS;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandServerStart extends Command
{

  /**
   * @var mixed|null
   */
  private $dir;

  /**
   * @param $dir
   */
  public function __construct($dir = null)
  {
    parent::__construct();
    $this->dir = $dir;
  }

  /**
   * @return void
   */
  protected function configure()
  {
    $this
      ->setName('server:start')
      ->setDescription('Server Start')
      ->setHelp('This command allows you to Server Start...');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int
   */
  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $host = "127.0.0.1:8686";
    $url = "http://$host";
    $file = ($this->dir ? $this->dir : "../Run.php");
    $file = FS::replace($file);
    $dir = FS::explode($file);
    $file_ = array_pop($dir);
    $dir = FS::implode($dir);
    $dir_ = trim(str_replace(trim(FS::app(), DIRECTORY_SEPARATOR), "", $dir), DIRECTORY_SEPARATOR);

    //$this->_kill("php");
    $this->_exec("php -S $host -t " . $dir_);
    $this->_exec("start $url");

    $output->writeln([
      '',
      'Sunucu "' . $dir_ . '" dizininde baslatildi.',
      '------------------------------',
      $host
    ]);

    return static::SUCCESS;
  }

  /**
   * @param string $cmd
   * @return void
   */
  private function _exec(string $cmd): void
  {
    if (substr(php_uname(), 0, 7) == "Windows") {
      pclose(popen("start /B " . $cmd, "r"));
    } else {
      exec($cmd . " > /dev/null &");
    }
  }
}