<?php
/*
 * This file bootstraps the test environment.
 */
namespace Slipstream\Tests;

error_reporting(E_ALL | E_STRICT);

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once __DIR__ . '/../../../lib/Slipstream/Common/ClassLoader.php';

$classLoader = new \Slipstream\Common\ClassLoader('Doctrine');
$classLoader->register();

set_include_path(
    __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'lib'
    . PATH_SEPARATOR .
    get_include_path()
);

