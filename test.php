<?php

require_once __DIR__ . '/vendor/autoload.php';

use BMVC\Libs\{MError, Benchmark};

MError::color("info")->print("Benchmark", Benchmark::memory(true));