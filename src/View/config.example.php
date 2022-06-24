<?php

BMVC\Libs\View::config([
  'path' => 'App\Http\View',
  'cache' => false,
  'theme' => 'default',
  'themes' => [
    'default' => [
      'path' => null,
      'layout' => 'Layout/Main.php'
    ]
  ]
]);