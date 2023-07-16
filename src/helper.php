<?php

use BMVC\Libs\Util;
use BMVC\Libs\Route;
use BMVC\Libs\View;
use BMVC\Libs\Locale;
use BMVC\Libs\Model;

/**
 * @return null|string
 */
function url(): ?string
{
  return Util::url(...func_get_args());
}

/**
 * @return null|string
 */
function page(): ?string
{
  return Util::page_url();
}

/**
 * @return void
 */
function pr()
{
  return Util::pr(...func_get_args());
}

/**
 * @return void
 */
function dump()
{
  return Util::dump(...func_get_args());
}

/**
 * @return void
 */
function redirect()
{
  return Util::redirect(...func_get_args());
}

/**
 * @return void
 */
function refresh()
{
  return Util::refresh(...func_get_args());
}

/**
 * @return void
 */
function date_to_time()
{
  return Util::date_to_time(...func_get_args());
}

/**
 * @return void
 */
function image_resize()
{
  return Util::image_resize(...func_get_args());
}

function getErrors()
{
  return Route::getErrors(...func_get_args());
}

function getRouteUrl()
{
  return Route::url(...func_get_args());
}

function getRU()
{
  return Route::url(...func_get_args());
}

function getViewData()
{
  return View::getData(...func_get_args());
}

function getVD()
{
  return View::getData(...func_get_args());
}

function getViewContent()
{
  return View::getContent();
}

function getVC()
{
  return View::getContent();
}

function getVA()
{
	return View::asset(...func_get_args());
}

function locales()
{
  return Locale::list(...func_get_args());
}

if (!function_exists('_')) {
  function _()
  {
    return func_get_arg(0);
  }
}

function iModel()
{
	return Model::import(...func_get_args());
}