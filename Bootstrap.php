<?php

// spl_autoload_register(function($c) {
//   $parts = explode(' ', strtr($c, '\\_', '  '));
//   $class = array_pop($parts) . '.php';
//   $path = implode("/", array_map("lcfirst", $parts)) . '/' . $class;
//   @include $path;
//
//   $path = strtr($c, '\\_', '//') . '.php';
//   @include $path;
// });

define("RUN_BASE",  dirname(__FILE__));

require (RUN_BASE . DIRECTORY_SEPARATOR . "Sabel"  . DIRECTORY_SEPARATOR . "Sabel.php");
require (RUN_BASE . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "INIT.php");
// require (RUN_BASE . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "environment.php");
define("ENVIRONMENT", TEST);
define("SBL_LOG_LEVEL", SBL_LOG_ALL);

unshift_include_path(RUN_BASE . "/tests");

require_once RUN_BASE . "/vendor/autoload.php";

if (!defined("ENVIRONMENT")) {
  echo "SABEL FATAL ERROR: must define ENVIRONMENT in config/environment.php";
  exit;
}

#Sabel_Bus::create()->run(new Config_Bus());

require_once RUN_BASE . "/app/helpers/application.php";
require_once RUN_BASE . "/app/helpers/utility.php";

Sabel_Db_Config::initialize(new Config_Database());
