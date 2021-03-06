<?php

use BMVC\Libs\Util;
use BMVC\Libs\Route;
use BMVC\Libs\View;

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

function getErrors()
{
  return Route::getErrors(...func_get_args());
}

function getViewData()
{
  return View::getData(...func_get_args());
}

function getViewContent()
{
  return View::getContent();
}

if (!function_exists('_')) {
  function _()
  {
    return func_get_arg(0);
  }
}