<?php

/**
 * Command Clear Log
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

class CommandClearLog extends Command
{

  /**
   * @return void
   */
  protected function configure()
  {
    $this
      ->setName('clear:log')
      ->setDescription('Logs Delete')
      ->setHelp('This command allows you to delete a log...');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int
   */
  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    array_map('unlink', glob(FS::app("Logs/*")));

    $output->writeln([
      '',
      'Logs dizini bosaltildi.',
      '------------------------------'
    ]);

    return static::SUCCESS;
  }
}