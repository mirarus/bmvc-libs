# Bmvc-libs

Mirarus BMVC Libs (Basic MVC Libs)

[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/mirarus/bmvc-libs?style=flat-square&logo=php)](https://packagist.org/packages/mirarus/bmvc-libs)
[![Packagist Version](https://img.shields.io/packagist/v/mirarus/bmvc-libs?style=flat-square&logo=packagist)](https://packagist.org/packages/mirarus/bmvc-libs)
[![Packagist Downloads](https://img.shields.io/packagist/dt/mirarus/bmvc-libs?style=flat-square&logo=packagist)](https://packagist.org/packages/mirarus/bmvc-libs)
[![Packagist License](https://img.shields.io/packagist/l/mirarus/bmvc-libs?style=flat-square&logo=packagist)](https://packagist.org/packages/mirarus/bmvc-libs)
[![PHP Composer](https://img.shields.io/github/workflow/status/mirarus/bmvc-libs/PHP%20Composer/main?style=flat-square&logo=php)](https://github.com/mirarus/bmvc-libs/actions/workflows/php.yml)


## Installation

Install using composer:

```bash
composer require mirarus/bmvc-libs
```

## Example

Install using composer:

```bash
<?php

	require_once __DIR__ . '/vendor/autoload.php';

	use BMVC\Core\{App, Route, Controller};
	use BMVC\Libs\{MError, Benchmark};

	class Main
	{
		function index() {
			echo "[Main::index]";
		}
	}

	Route::any('/', function () {
		Controller::call('main@index');
		MError::color("info")::print("Benchmark", "Memory Usage: " . Benchmark::memory());
	});

	App::Run([
	'init' => [
	//BMVC\Core\Model::class
	]
]);
?>
```

## License

Licensed under the MIT license, see [LICENSE](LICENSE)
