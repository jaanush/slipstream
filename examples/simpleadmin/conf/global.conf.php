<?php

if (is_file(dirname(__FILE__) . '/local.paths.php')) {
  require_once dirname(__FILE__) . '/local.paths.php';
} else {
  require_once dirname(__FILE__) . '/development.paths.php';
}
require_once 'konstrukt/konstrukt.inc.php';
require_once('Slipstream/Common/ClassLoader.php');
$classLoader = new \Slipstream\Common\ClassLoader();
$classLoader->register();
//set_error_handler('k_exceptions_error_handler');
//spl_autoload_register('k_autoload');

// This loads the site-configuration. By default, it will load the development environment.
//
// You shouldn't alter this file or `development.inc.php`.
// Instead, create a file called `SITENAME.inc.php`, where SITENAME is the name of the site.
// Check all `SITENAME.inc.php` into the repository.
// On each site (server), create a link:
//
//     ln -s SITENAME.inc.php local.inc.php
//
// If your server doesn't support symlinks (Windows), you can instead use:
//
//   copy default.inc.php local.inc.php
//
//, and change the include inside `local.inc.php`.
//
// Don't check `local.inc.php` into the repository.
//
$debug_log_path = null;
$debug_enabled = false;
if (is_file(dirname(__FILE__) . '/local.conf.php')) {
  require_once dirname(__FILE__) . '/local.conf.php';
} else {
  require_once dirname(__FILE__) . '/development.conf.php';
}

