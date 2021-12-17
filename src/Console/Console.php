<?php

/**
 * Console
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Console
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.1
 */

namespace BMVC\Libs\Console;

use BMVC\Libs\FS;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class Console extends Application
{

	/**
	 * @phpstan-ignore-next-line
	 */
	public function __construct($dir=null)
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

class CommandServerStart extends Command
{

	/**
	 * @var string
	 */
	private $dir;

	/**
	 * @phpstan-ignore-next-line
	 */
	public function __construct($dir=null)
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

	private function _exec(string $cmd): void
	{
		if (substr(php_uname(), 0, 7) == "Windows") {
			pclose(popen("start /B " . $cmd, "r"));   // @phpstan-ignore-line
		} else {
			exec($cmd . " > /dev/null &");
		}
	}
}

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

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		pr($this->_kill("php"));

		$output->writeln([
			'',
			'Server durduruldu.',
			'------------------------------'
		]);

		return static::SUCCESS;
	}

	/**
	 * @phpstan-ignore-next-line
	 *
	 * @psalm-param 'php' $cmd
	 */
	private function _kill(string $cmd)
	{
		exec("taskkill /F /T /IM $cmd.exe > /dev/null &");
	}
}

class CommandMakeController extends Command
{

	/**
	 * @return void
	 */
	protected function configure()
	{
		$this
		->setName('make:controller')
		->setDescription('Controller Create')
		->setHelp('This command allows you to create a controller...')
		->addArgument('class', InputArgument::REQUIRED, 'Class is required');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$data = CmakeCM('controller', $input->getArgument('class')); // @phpstan-ignore-line

		if ($data['status']) {
			$output->writeln([
				'',
				'create a controller.',
				'------------------------------',
				$data['file']
			]);
		} else {
			$output->writeln([
				'',
				'create a controller error.',
				'------------------------------',
				$data['file']
			]);
		}

		return static::SUCCESS;
	}
}

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

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$data = CmakeCM('model', $input->getArgument('class')); // @phpstan-ignore-line

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
}

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

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		array_map('unlink', glob(FS::app("Logs/*"))); // @phpstan-ignore-line

		$output->writeln([
			'',
			'Logs dizini bosaltildi.',
			'------------------------------'
		]);

		return static::SUCCESS;
	}
}


/**
 * @param string $type
 * @param string $class
 * @return (bool|string)[]
 *
 * @psalm-return array{status: bool, file: string}
 */
function CmakeCM(string $type, string $class): array
{
	$useLib = ucfirst($type);

	$class = FS::replace($class);
	$file = $class . '.php';

	$parts = FS::explode($class);
	$class = array_pop($parts);
	$ns    = FS::implode($parts);

	if (file_exists($file)) {

		return [
			'status' => false,
			'file' => $file
		];
	} else {

		$ns ? FS::mk_dir($ns) : null;
		$namespace = $ns ? "namespace $ns;\n\n" : null;

		$f = fopen($file, 'w');
		$content = "<?php\n\n{$namespace}use BMVC\\Core\\{$useLib};\n\nclass $class\n{\n\n\tpublic function index()\n\t{\n\t\t\n\t}\n}";
		fwrite($f, $content); // @phpstan-ignore-line
		fclose($f); // @phpstan-ignore-line

		return [
			'status' => true,
			'file' => $file
		];
	}
}

/**
 * @param string  $command
 * @param integer $timeout
 * @param integer $sleep
 */
function PsExecute(string $command, $timeout = 60, $sleep = 2): bool
{

	$pid = PsExec($command);

	if( $pid === false )
		return false;

	$cur = 0;
	while( $cur < $timeout ) {
		sleep($sleep);
		$cur += $sleep;

		echo "\n ---- $cur ------ \n";

		if( !PsExists($pid) )
			return true;
	}
	PsKill($pid);
	return false;
}

/**
 * @param string $commandJob
 * @return false|int
 */
function PsExec(string $commandJob) {
	$command = $commandJob.' > /dev/null 2>&1 & echo $!';
	exec($command, $op);
	$pid = (int)$op[0];
	if($pid!="") return $pid;
	return false;
}


/**
 * @param int $pid
 */
function PsExists(int $pid): bool
{
	exec("ps ax | grep $pid 2>&1", $output);
	while( list(,$row) = each($output) ) {
		$row_array = explode(" ", $row);
		$check_pid = $row_array[0];

		if($pid == $check_pid) {
			return true;
		}
	}
	return false;
}
	
/**
 * @param int $pid
 */
function PsKill(int $pid): void {
	exec("kill -9 $pid", $output);
}