<?php

/**
 * Command Server Stop
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Console
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandServerStop extends Command
{

  /**
   * @return void
   */
  protected function configure()
  {
    $this
      ->setName('server:stop')
      ->setDescription('Server Stop')
      ->setHelp('This command allows you to Server Stop...');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int
   */
  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    pr(exec("taskkill /F /T /IM php.exe > /dev/null &"));

    $output->writeln([
      '',
      'Server durduruldu.',
      '------------------------------'
    ]);

    return static::SUCCESS;
  }
}