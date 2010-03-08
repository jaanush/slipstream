<pre><?php
ob_start();
use \Slipstream\JsonRest;
set_include_path(get_include_path().':/home/jaanush/libs/php/active:'.__DIR__.'/lib');
print("\nNew testrun\n");
print('Include path: '.get_include_path()."\n");
require_once('Slipstream/Common/ClassLoader.php');
require_once('Bucket.php');
//require_once('Slipstream/Common/Error/Handler.php');
$doctrineClassLoader = new \Slipstream\Common\ClassLoader();
$doctrineClassLoader->register();

$config= new \Slipstream\JsonRest\Configuration();
//print('Doctrine autoloading '.(class_exists('Doctrine\Common\Util\Debug')?'works':'error')."\n");
//print('Slipstream autoloading '.(class_exists('Slipstream\Common\Error\Handler')?'works':'error')."\n");
$j=new Slipstream\JsonRest\JsonRest($config);
$j->run();
$j->log()->log($_SERVER);
?></pre>