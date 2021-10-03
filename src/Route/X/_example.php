<?php

use BMVC\Libs\Route\Route;

Route::get('a/:all', function () {});
Route::get('a', function () {});
Route::post('a', function () {});
Route::post('a', function () {});
Route::get('aa', function () {});
Route::get('aa', function () {});
Route::post('aa', function () {});
Route::post('aasda', function () {});
Route::prefix("dsasd/:id")->get('aa', function () {});
//Route::ip("127.0.0.1")->get('aa', function () {});

echo "<pre>";

print_r(Route::routes());

$route = Route::run();

if ($route) {
	echo "<br>";
	print_r($route);

} else {
	echo "404 Page Error!!";
}