<?php

/**
 * Command Make Model
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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandMakeModel extends Command
{
  /**
   * @return void
   */
  protected function configure()
  {
    $this
      ->setName('make:model')
      ->setDescription('Model Create')
      ->setHelp('This command allows you to create a model...')
      ->addArgument('class', InputArgument::REQUIRED, 'Class is required');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int
   */
  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $data = $this->makeClass($input->getArgument('class'));

    if ($data['status']) {
      $output->writeln([
        '',
        'create a model.',
        '------------------------------',
        $data['file']
      ]);
    } else {
      $output->writeln([
        '',
        'create a model error.',
        '------------------------------',
        $data['file']
      ]);
    }

    return static::SUCCESS;
  }

  /**
   * @param string $class
   * @return array
   */
  private function makeClass(string $class): array
  {
    $class = FS::replace($class);
    $file = $class . '.php';

    $parts = FS::explode($class);
    $class = array_pop($parts);
    $ns = FS::implode($parts);

    if (file_exists($file)) {

      return [
        'status' => false,
        'file' => $file
      ];
    } else {

      if ($ns) FS::mk_dir($ns);
      $namespace = $ns ? "namespace $ns;\n\n" : null;

      $f = fopen($file, 'w');
      $content = "<?php\n\n{$namespace}use BMVC\\Core\\Model;\n\nclass $class\n{\n\n\tpublic function index()\n\t{\n\t\t\n\t}\n}";
      fwrite($f, $content);
      fclose($f);

      return [
        'status' => true,
        'file' => $file
      ];
    }
  }
}