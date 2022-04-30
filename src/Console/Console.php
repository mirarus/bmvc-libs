<?php

/**
 * Console
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Console
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.2
 */

namespace BMVC\Libs\Console;

use Symfony\Component\Console\Application;

class Console extends Application
{

  /**
   * @param $dir
   * @throws \Exception
   */
  public function __construct($dir = null)
  {
    parent::__construct('BMVC', '@Beta');
    $this->setAutoExit(false);

    $this->add(new CommandServerStart($dir));
    //	$this->add(new CommandServerStop());
    $this->add(new CommandMakeController());
    $this->add(new CommandMakeModel());
    $this->add(new CommandClearLog());

    $this->run();
  }
}