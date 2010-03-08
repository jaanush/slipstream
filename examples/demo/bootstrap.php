<?php
$ss_timer=microtime(true);
ob_start();
define('SS_ROOT',dirname(__DIR__));
set_include_path(get_include_path().':/home/jaanush/libs/php/active:'.SS_ROOT.'/lib:'.SS_ROOT);
//print("\nNew testrun\n");
//print('Include path: '.get_include_path()."\n");
require_once('Slipstream/Common/ClassLoader.php');
require_once('Bucket.php');
require_once('domaindef.php');
//require_once('Slipstream/Common/Error/Handler.php');
$classLoader = new \Slipstream\Common\ClassLoader();
$classLoader->register();